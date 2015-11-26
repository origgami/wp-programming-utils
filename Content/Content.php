<?php

namespace OriggamiWpProgrammingUtils\Content;

if (!class_exists('\OriggamiWpProgrammingUtils\Content\Content')) {

	/**
	 * Description of Content
	 *
	 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
	 */
	class Content {

		private $contents = array();

		public function __construct() {
			new Shortcodes\Shortcodes($this);
			add_filter('post_class', array($this, 'getPostSlug'));
			add_action('wp_footer', array($this, 'replaceLinks'));
		}

		public function replaceLinks() {
			$contents = $this->getContents();
			?>
			<script>
				var contents = <?php echo wp_json_encode($contents); ?>;
				for (key in contents) {
					var item = contents[key];
					if (item['bind'] != false && item['bind'] != 'false') {
						var element = 'a';
						if (item['bind'] == 'menus') {
							element = 'ul li a';
						}
						jQuery('' + element + '[href="' + item['link'] + '"]').attr('href', '#' + item['slug']);
					}
				}

				jQuery(window).load(function ($) {
				    handleMenuActiveItem();				    
				});

				function handleMenuActiveItem() {
				    var contentItems = [];    
				    var offset=70;
				    var currItemAnchor;
				    jQuery('.nav .menu-item a').each(function () {
				        var currLink = jQuery(this);
				        var currLinkHref = currLink.attr('href');
				        
				        if(currLinkHref.indexOf('#')!=-1){
				            var linkAnchor = currLinkHref.substr(currLinkHref.indexOf('#') + 1);
				            var itemContentOffsetTop = jQuery('#' + linkAnchor).offset().top;
				            var itemContentHeight = jQuery('#' + linkAnchor).height();
				            var itemInf = {};
				            itemInf.anchor = linkAnchor;
				            itemInf.offsetTop = itemContentOffsetTop;
				            itemInf.height = itemContentHeight;
				            itemInf.href = currLinkHref;
				            itemInf.id = currLink.parent().attr('id');
				            contentItems.push(itemInf);            
				        }
				    });

				    jQuery(window).on('scroll', function () {
				        var win = jQuery(window);				        
				        for (var i in contentItems) {
				            var item = contentItems[i];
				            if (win.scrollTop() > (item.offsetTop - offset) && currItemAnchor!=item.anchor) {				                
				                currItemAnchor=item.anchor;
				                jQuery('.nav .menu-item').removeClass('current-menu-item current_page_item active');
				                jQuery('#' + item.id).addClass('current-menu-item current_page_item active');
				            }
				        }
				    });
				    jQuery(window).trigger('scroll');
				    jQuery(window).scroll();
				}
			</script>
			<?php
		}

		//Inclui o slug do post nas classes do post
		function getPostSlug($classes) {
			global $post;
			$classes[] = $post->post_name;
			return $classes;
		}

		public function getContent($args = null) {
			$args		 = wp_parse_args($args, array(
				'post_type'			  => 'page',
				'posts_per_page' 	  => 1,
				'bind'				  => 'menus', //menus | all | false
				'highlight_menu_item' =>  true,
				'template'		      => 'default',
			));
			$bindLinks	 = $args['bind'];
			$contents	 = $this->getContents();
			wp_reset_postdata();
			ob_start();
			$gcQuery	 = new \WP_Query($args);
			if ($gcQuery->have_posts()) {
				while ($gcQuery->have_posts()) {
					global $post;
					$gcQuery->the_post();
					$is_loaded_from_origgami_get_content = true;

					$contents[] = array(
						'slug'	 => $post->post_name,
						'title'	 => get_the_title(),
						'bind'	 => $bindLinks,
						'highlight_menu_item' =>  $args['highlight_menu_item'],
						'link'	 => get_the_permalink()
					);

					$template = $args['template'];
					if ($template == 'default') {
						$template = get_post_meta(get_the_ID(), '_wp_page_template', TRUE);
						if ($template == 'default') {
							$template = 'page.php';
						}
					}
					echo '<a id="' . $post->post_name . '"></a>';
					require( locate_template($template) );
				}
			} else {
				// no posts found
			}
			wp_reset_postdata();

			$var = ob_get_contents();
			ob_end_clean();
			$this->setContents($contents);
			return $var;
		}

		function getContents() {
			return $this->contents;
		}

		function setContents($contents) {
			$this->contents = $contents;
		}

	}

}