<?php

namespace OriggamiWpProgrammingUtils\Dependency;

/**
 * Description of DependencyManager
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */
if ( !class_exists('\OriggamiWpProgrammingUtils\Dependency\DependencyManager') ) {

	class DependencyManager {

		private $projectName;
		private $dependencies = array();
		private $missingResources = array();

		public function __construct( $projectName ) {
			$this->setProjectName($projectName);
		}

		public function addDependency( $args = array() ) {
			$dependencies = $this->getDependencies();
			$args = wp_parse_args($args, array(
				'type'			 => 'plugin',
				'name'			 => 'Origgami Wp Programming Utils',
				'required'		 => true,
				'url'			 => '',
				'test_method'	 => array(
					// is_plugin_active | class_exists | function_exists | whatever method you want
					'class_exists' => '\OriggamiWpProgrammingUtils\OriggamiWpProgrammingUtils'
				)
			));
			$dependencies[] = $args;
			$this->setDependencies($dependencies);
		}

		private function checkDependency( $value ) {
			$isOk = false;
			$testMethod = key($value['test_method']);
			$testValue = reset($value['test_method']);
			$isOk = call_user_func($testMethod, $testValue);
			return $isOk;
		}

		public function showNotices( $required = true ) {
			global $pagenow;

			if ( $pagenow != 'plugins.php' )
				return;

			$dependencies = $this->getDependencies();
			$class = $required ? "error" : "updated";
			$projectName = $this->getProjectName();
			$message = $required ? "<p>Error. <b>{$projectName}</b> needs these resources:</p>" : "<p>Alert. <b>{$projectName}</b> recommends these resources:</p>";
			$message.='<ul style="list-style:inside">';
			foreach ( $dependencies as $key => $value ) {
				if ( !$value['enabled'] && $value['required'] == $required ) {
					$messageContent = $value['url'] ? "<a href='{$value['url']}'>{$value['name']}</a>" : "{$value['name']}";
					$message.= "<li>{$value['type']}: <b>{$messageContent}</b></li>";
				}
			}
			$message.='</ul>';
			$borderColor = $required ? '' : 'style="border-left: 4px solid #ffba00"';
			echo"<div {$borderColor} class=\"$class\">$message</div>";
		}

		public function showOptionalNotices() {
			$this->showNotices(false);
		}

		public function showRequiredNotices() {
			$this->showNotices(true);
		}

		public function check() {
			$dependencies = $this->getDependencies();
			$activePlugins = apply_filters('active_plugins', get_option('active_plugins'));
			$allRequiredDependenciesAreEnabled = true;
			$allOptionalDependenciesAreEnabled = true;
			$missingResources = $this->getMissingResources();
			foreach ( $dependencies as $key => $value ) {
				$dependencyIsOk = $this->checkDependency($value);
				$dependencies[$key]['enabled'] = $dependencyIsOk;
				if ( !$dependencyIsOk && $value['required'] ) {
					$allRequiredDependenciesAreEnabled = false;
					$missingResources[] = $value;
				} else if ( !$value['required'] ) {
					$allOptionalDependenciesAreEnabled = false;
				}
			}
			$this->setMissingResources($missingResources);
			$this->setDependencies($dependencies);
			if ( !$allRequiredDependenciesAreEnabled ) {
				add_action('admin_notices', array($this, 'showRequiredNotices'));
			}
			if ( !$allOptionalDependenciesAreEnabled ) {
				add_action('admin_notices', array($this, 'showOptionalNotices'));
			}
		}

		function getProjectName() {
			return $this->projectName;
		}

		function setProjectName( $projectName ) {
			$this->projectName = $projectName;
		}

		function getDependencies() {
			return $this->dependencies;
		}

		function setDependencies( $dependencies ) {
			$this->dependencies = $dependencies;
		}

		function getMissingResources() {
			return $this->missingResources;
		}

		function setMissingResources( $missingResources ) {
			$this->missingResources = $missingResources;
		}

	}

	class DependencyArgs {

		public static $type = 'plugin';

		/* 'type'			 => 'plugin',
		  'name'			 => 'Origgami Wp Programming Utils',
		  'required'		 => true,
		  'url'			 => 'https://github.com/origgami/wp-programming-utils',
		  'test_method'	 => array('class_exists' => '\OriggamiWpProgrammingUtils\OriggamiWpProgrammingUtilss') */
	}

}
