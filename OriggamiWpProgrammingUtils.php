<?php

/**
 * Plugin Name: Origgami Wp Programming Utils
 * Plugin URI: http://origgami.com.br
 * Description: Programming Utilities for Wordpress
 * Version: 1.0.0
 * Author: Origgami
 * Author URI: http://origgami.com.br
 * Text Domain: 
 * Domain Path: /i18n
 * Network: 
 * License: GPL2
 * Bitbucket Plugin URI: https://bitbucket.org/origgamiwordpressplugins/origgami-wp-programming-utils
 * Bitbucket Branch:     master
 */

namespace OriggamiWpProgrammingUtils;

if (!class_exists('\OriggamiWpProgrammingUtils\OriggamiWpProgrammingUtils')) {
	require_once dirname(__FILE__) . '/DesignPatterns/Singleton.php';

//require './oop_functions/OOPFunctions.php';

	/**
	 * Description of WpProgrammingTools
	 *
	 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
	 */
	class OriggamiWpProgrammingUtils extends DesignPatterns\Singleton {

		private $autoload;
		private $oopFunctions;
		private $utils;

		protected function __construct() {
			parent::__construct();
			add_action('activated_plugin', array($this, 'my_plugin_load_first'));
			$this->loadUsefulFiles();
			$this->handleAutoload();
		}

		function my_plugin_load_first() {
			//$path	 = str_replace(WP_PLUGIN_DIR . '/', '', __FILE__);
			$path	 = plugin_basename(dirname(__FILE__)) . '/' . basename(__FILE__);
			$plugins = get_option('active_plugins');
			if ($plugins) {
				if ($key = array_search($path, $plugins)) {
					array_splice($plugins, $key, 1);
					array_unshift($plugins, $path);
					update_option('active_plugins', $plugins);
				}
			}
		}

		private function loadUsefulFiles() {
			require_once dirname(__FILE__) . '/Autoload/WpAutoload.php';
			require_once dirname(__FILE__) . '/PhpFunctions/php_functions.php';
		}

		private function handleAutoload() {
			if (!$this->getAutoload()) {
				$autoload	 = new Autoload\WpAutoload(array(get_stylesheet_directory(), WP_PLUGIN_DIR));
				$dir		 = basename(dirname(__FILE__));
				$autoload->addNamespaceReplace(array('OriggamiWpProgrammingUtils' => $dir));
				$autoload->handleAutoload();
				$this->setAutoload($autoload);
			}
		}

		/**
		 * 
		 * @return Autoload\WpAutoload;
		 */
		function getAutoload() {
			return $this->autoload;
		}

		function setAutoload(Autoload\WpAutoload $autoload) {
			$this->autoload = $autoload;
		}

		/**
		 * 
		 * @return Utils\Utils
		 */
		function getUtils() {
			if (!$this->utils) {
				$this->setUtils(new Utils\Utils());
			}
			return $this->utils;
		}

		function setUtils(Utils\Utils $utils) {
			$this->utils = $utils;
		}

	}

	$programmingTools = \OriggamiWpProgrammingUtils\OriggamiWpProgrammingUtils::getInstance();
}
