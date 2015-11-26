<?php

namespace OriggamiWpProgrammingUtils\Content\Shortcodes;

if (!class_exists('\OriggamiWpProgrammingUtils\Content\Shortcodes\Shortcodes')) {

	/**
	 * Description of Shortcodes
	 *
	 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
	 */
	class Shortcodes {

		/**
		 *
		 * @var \OriggamiWpProgrammingUtils\Content\Content
		 */
		private $content;

		public function __construct(\OriggamiWpProgrammingUtils\Content\Content $content) {
			$this->setContent($content);
			add_shortcode('origgami_get_content', array($this, 'origgamiGetContent'));
		}

		public function origgamiGetContent($atts) {

			extract(shortcode_atts(
			array(
				'post_type'			  => 'page',
				'posts_per_page' 	  => 1,
				'bind'				  => 'menus', //menus | all | false
				'highlight_menu_item' =>  true,
				'template'		      => 'default',
			), $atts));

			//return 'asdasds';
			return $this->getContent()->getContent($atts);
		}

		function getContent() {
			return $this->content;
		}

		function setContent(\OriggamiWpProgrammingUtils\Content\Content $content) {
			$this->content = $content;
		}

	}

}