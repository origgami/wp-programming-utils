<?php

namespace OriggamiWpProgrammingUtils\OOP\PostType\AdminImageColumn;

if ( !class_exists('\OriggamiWpProgrammingUtils\OOP\PostType\AdminImageColumn\AdminImageColumn') ) {

	/**
	 * Cria uma coluna no admin para um post type, responsavel por exibir uma imagem q pode ser ou do thumbnail ou de um meta do post
	 *
	 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
	 */
	class AdminImageColumn {

		private $args;

		public function __construct( $args = null ) {
			$args = wp_parse_args($args, array(
				'cpt_slug'	 => null,
				'image_meta' => null,
				'column_id'	 => 'og_thumb',
				'label'		 => __('Thumbnail'),
				'img_size'		 => array(60, 60),
				'col_width'		 => 75,
			));
			$this->setArgs($args);
		}

		public function register() {
			$args = $this->getArgs();
			add_filter("manage_{$args['cpt_slug']}_posts_columns", array($this, 'postsColumns'));
			add_action("manage_{$args['cpt_slug']}_posts_custom_column", array($this, 'customColumn'), 10, 2);
			add_action('admin_head', array($this, 'adminHead'));
		}

		public function adminHead() {
			$args = $this->getArgs();
			?>
			<style>
				.column-<?php echo $args['column_id'] ?>{
					width:<?php echo $args['col_width'] ?>px;
					text-align:center !important;
				}
				.content-<?php echo $args['column_id'] ?>{
					text-align:center;
					margin:0 auto;
					box-sizing: border-box;
					-moz-box-sizing: border-box;
					-webkit-box-sizing: border-box;
					border: 3px solid #ccc;
					background-size:contain;
					width:<?php echo $args['img_size'][0]; ?>px;
					height:<?php echo $args['img_size'][1]; ?>px;
				}
			</style>
			<?php
		}

		public function customColumn( $column_name, $postID ) {
			$args = $this->getArgs();
			if ( $column_name == $args['column_id'] ) {
				$post_thumbnail_id = get_post_thumbnail_id($postID);
				//$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
				$image = wp_get_attachment_image_src($post_thumbnail_id);
				?>
				<div class="content-<?php echo $args['column_id'] ?>" style="background-image:url(<?php echo $image[0]; ?>)"></div>
				<?php
			}
		}

		public function postsColumns( $columns ) {
			$args = $this->getArgs();
			$afterIndex = 0;
			$newVal = array($args['column_id'] => $args['label']);
			$newList = array_merge(array_slice($columns, 0, $afterIndex + 1), $newVal, array_slice($columns, $afterIndex + 1));
			return $newList;
		}

		function getArgs() {
			return $this->args;
		}

		function setArgs( $args ) {
			$this->args = $args;
		}

	}

}