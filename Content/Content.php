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
				'post_type'		 => 'page',
				'posts_per_page' => 1,
				'bind_links'	 => 'menus', //menus | all | false
				'template'		 => 'default'
			));
			$bindLinks	 = $args['bind_links'];
			$contents	 = $this->getContents();
			wp_reset_postdata();
			ob_start();
			$the_query	 = new \WP_Query($args);
			if ($the_query->have_posts()) {
				while ($the_query->have_posts()) {
					global $post;
					$the_query->the_post();
					$is_loaded_from_origgami_get_content = true;

					$contents[] = array(
						'slug'	 => $post->post_name,
						'title'	 => get_the_title(),
						'bind'	 => $bindLinks,
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