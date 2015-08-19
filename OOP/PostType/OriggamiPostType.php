<?php

namespace OriggamiWpProgrammingUtils\OOP\PostType;

/**
 * Description of PostType
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */
if ( !class_exists('\OriggamiWpProgrammingUtils\OOP\PostType\OriggamiPostType') ) {


	class OriggamiPostType {

		//put your code here

		private $id = 'cpt';
		private $args = array();
		private $labels = array();
		private $nameInfs = array();
		private $textDomain = 'origgami';

		public function register() {
			add_action('init', array($this, 'registerPostType'));
		}

		public function __construct( $id, $nameInfs, $textDomain = 'default' ) {
			$this->setId($id);
			$this->setTextDomain($textDomain);
			$nameInfsDefaults = array(
				'singular'	 => 'Coisa',
				'plural'	 => $nameInfs['singular'] . 's'
			);
			$nameInfs = wp_parse_args($nameInfs, $nameInfsDefaults);
			$this->setNameInfs($nameInfs);
		}

		public function registerPostType() {
			register_post_type($this->getId(), $this->getArgs());
		}

		function getId() {
			return $this->id;
		}

		function getArgs() {
			$defaults = array(
				'labels'				 => $this->getLabels(),
				'hierarchical'			 => false,
				'supports'				 => array('title', 'editor', 'thumbnail', 'comments', 'revisions'),
				'public'				 => true,
				'show_ui'				 => true,
				'show_in_menu'			 => true,
				'show_in_nav_menus'		 => false,
				'publicly_queryable'	 => true,
				'exclude_from_search'	 => false,
				'has_archive'			 => true,
				'query_var'				 => true,
				'can_export'			 => true,
				'rewrite'				 => true,
				'capability_type'		 => 'post'
			);
			$args = wp_parse_args($this->args, $defaults);
			return $args;
		}

		function getLabels() {
			$nameInfs = $this->getNameInfs();
			$labelsDefaults = array(
				'name'				 => _x($nameInfs['plural'], 'post type general name', $this->getTextDomain()),
				'singular_name'		 => _x($nameInfs['singular'], 'post type singular name', $this->getTextDomain()),
				'menu_name'			 => _x($nameInfs['plural'], 'admin menu', $this->getTextDomain()),
				'name_admin_bar'	 => _x($nameInfs['singular'], 'add new on admin bar', $this->getTextDomain()),				
				'add_new'			 => __('Add',$this->getTextDomain()).' '.__($nameInfs['singular'],$this->getTextDomain()),
				'add_new_item'		 => __('New',$this->getTextDomain()).' '.__($nameInfs['singular'],$this->getTextDomain()),
				'new_item'			 => __('New',$this->getTextDomain()).' '.__($nameInfs['singular'],$this->getTextDomain()),
				'edit_item'			 => __('Edit',$this->getTextDomain()).' '.__($nameInfs['singular'],$this->getTextDomain()),
				'view_item'			 => __('View',$this->getTextDomain()).' '.__($nameInfs['singular'],$this->getTextDomain()),
				'all_items'			 => __($nameInfs['plural'], $this->getTextDomain()),
				'search_items'		 => __('Search',$this->getTextDomain()).' '.__($nameInfs['plural'],$this->getTextDomain()),
				'parent_item_colon'	 => __('Parent',$this->getTextDomain()).' '.__($nameInfs['plural'],$this->getTextDomain()),
				'not_found'			 => __('Nothing found.', $this->getTextDomain()),
				'not_found_in_trash' => __('Nothing found.', $this->getTextDomain())
			);
			$labels = wp_parse_args($this->labels, $labelsDefaults);
			return $labels;
		}

		function getNameInfs() {
			return $this->nameInfs;
		}

		function setId( $id ) {
			$this->id = $id;
		}

		function setArgs( $args ) {
			$this->args = $args;
		}

		function setLabels( $labels ) {
			$this->labels = $labels;
		}

		function setNameInfs( $nameInfs ) {
			$this->nameInfs = $nameInfs;
		}

		function getTextDomain() {
			return $this->textDomain;
		}

		function setTextDomain( $textDomain ) {
			$this->textDomain = $textDomain;
		}

	}

}
