<?php

namespace OriggamiWpProgrammingUtils\Autoload;

/**
 * Description of WpAutoload
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */
if ( !class_exists('\OriggamiWpProgrammingUtils\Autoload\Autoload') ) {

	class WpAutoload {

		private $folders = array();
		private $replaces = array();

		public function addFolder( $folder ) {
			$folders = $this->getFolders();
			$folders[] = $folder;
			$this->setFolders($folders);
		}

		public function addNamespaceReplace( $namespaceReplaces = array() ) {
			$replaces = $this->getReplaces();
			$replaces = array_merge($namespaceReplaces, $replaces);
			$this->setReplaces($replaces);
		}

		public function __construct( $folders = array(), $replaces = array() ) {
			$this->setFolders($folders);
			$this->setReplaces($replaces);
		}

		public function handleAutoload() {
			spl_autoload_unregister(array($this, 'autoloadFunction'));
			spl_autoload_register(array($this, 'autoloadFunction'));
		}

		private function requireFile( $filePath ) {
			if ( file_exists($filePath) ) {
				require $filePath;
				return true;
			} else {
				return false;
			}
		}

		public function autoloadFunction( $class ) {
			if ( !class_exists($class) ) {
				$folders = $this->getFolders();
				$replaces = $this->getReplaces();
				foreach ( $folders as $key => $folder ) {
					$filePath = $folder . DIRECTORY_SEPARATOR . $class . '.php';
					$filePath = str_replace('\\', '/', $filePath);
					if ( $this->requireFile($filePath) ) {
						break;
						//$filePath = strtolower($filePath);
						//$this->requireFile($filePath);
					} else {
						foreach ( $replaces as $key => $replace ) {
							$filePathReplaced = str_replace($key, $replace, $filePath);
							if ( $this->requireFile($filePathReplaced) ) {
								break;
							}
						}
					}
				}
			}
		}

		function getReplaces() {
			return $this->replaces;
		}

		function setReplaces( $replaces ) {
			$this->replaces = $replaces;
		}

		function getFolders() {
			return $this->folders;
		}

		function setFolders( $folders ) {
			$this->folders = $folders;
		}

	}

}