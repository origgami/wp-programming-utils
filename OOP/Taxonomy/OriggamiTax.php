<?php

namespace OriggamiWpProgrammingUtils\OOP\Taxonomy;

/**
 * Description of OriggamiTax
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */
if ( !class_exists('\OriggamiWpProgrammingUtils\OOP\Taxonomy\OriggamiTax') ) {

	class OriggamiTax {

		private $id = 'cpt';
		private $args = array();
		private $labels = array();
		private $nameInfs = array();
		private $textDomain = 'origgami';
		private $objectType = array();

		public function register() {
			add_action('init', array($this, 'registerTax'));
		}

		public function __construct( $id, $nameInfs, $objectType, $textDomain = 'default' ) {
			$this->setId($id);
			$this->setObjectType($objectType);
			$this->setTextDomain($textDomain);
			$nameInfsDefaults = array(
				'singular'	 => 'Coisa',
				'plural'	 => $nameInfs['singular'] . 's'
			);
			$nameInfs = wp_parse_args($nameInfs, $nameInfsDefaults);
			$this->setNameInfs($nameInfs);
		}

		public function registerTax() {
			register_taxonomy($this->getId(), $this->getObjectType(), $this->getArgs());
		}

		function getId() {
			return $this->id;
		}

		function getArgs() {
			$defaults = array(
				'labels'			 => $this->getLabels(),
				'hierarchical'		 => true, // Like categories.
				'public'			 => true,
				'show_ui'			 => true,
				'show_admin_column'	 => true,
				'show_in_nav_menus'	 => true,
				'show_tagcloud'		 => true,
			);
			$args = wp_parse_args($this->args, $defaults);
			return $args;
		}

		function getLabels() {
			$nameInfs = $this->getNameInfs();
			$labelsDefaults = array(
				'name'						 => sprintf(__('%s', $this->getTextDomain()), $nameInfs['plural']),
				'singular_name'				 => sprintf(__('%s', $this->getTextDomain()), $nameInfs['singular']),
				'add_or_remove_items'		 => sprintf(__('Adicionar ou remover %s', $this->getTextDomain()), $nameInfs['plural']),
				'view_item'					 => sprintf(__('Ver %s', $this->getTextDomain()), $nameInfs['singular']),
				'edit_item'					 => sprintf(__('Editar %s', $this->getTextDomain()), $nameInfs['singular']),
				'search_items'				 => sprintf(__('Buscar %s', $this->getTextDomain()), $nameInfs['singular']),
				'update_item'				 => sprintf(__('Atualizar %s', $this->getTextDomain()), $nameInfs['singular']),
				'parent_item'				 => sprintf(__('%s pai:', $this->getTextDomain()), $nameInfs['singular']),
				'parent_item_colon'			 => sprintf(__('%s pai:', $this->getTextDomain()), $nameInfs['singular']),
				'menu_name'					 => sprintf(__('%s', $this->getTextDomain()), $nameInfs['plural']),
				'add_new_item'				 => sprintf(__('Adicionar %s', $this->getTextDomain()), $nameInfs['singular']),
				'new_item_name'				 => sprintf(__('Novo %s', $this->getTextDomain()), $nameInfs['singular']),
				'all_items'					 => sprintf(__('Todos os %s', $this->getTextDomain()), $nameInfs['plural']),
				'separate_items_with_commas' => sprintf(__('Separar por vírgula', $this->getTextDomain()), $nameInfs['singular']),
				'choose_from_most_used'		 => __('Escolher dos mais usados', $this->getTextDomain())
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

		function getObjectType() {
			return $this->objectType;
		}

		function setObjectType( $objectType ) {
			$this->objectType = $objectType;
		}

	}

}
