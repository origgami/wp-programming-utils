<?php

namespace OriggamiWpProgrammingUtils\Dependency;

/**
 * Description of DependencyChecker
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */
class DependencyChecker {

	//put your code here

	private $dependencies = array();
	private $missingPlugins = array();
	private $pluginName = '';
	private $showAdminMessages = true;

	/**
	 * 
	 * @param array $dependencies Array of plugins needed
	 * Form the array like this:
	 * <code>
	 * $dependencies = 
	 * array(
	 *       'url'=>'https://wordpress.org/plugins/attachments/',
	 *       'name'=>'Attachments',
	 *       'type'=>'plugin',
	 *       'test_methods'=>array(
	 *           'relation'=>'OR',
	 *           array('class_exists'=>'Attachments'),
	 *           array('is_plugin_active'=>'attachments/index.php')
	 *           array('function_exists'=>'mr_image_resize')
	 *       )
	 *   )
	 * </code>     
	 * @param type $pluginName Name of your plugin or theme or anything else
	 * @param type $showAdminMessages Show admin messages about the missing plugins, or not
	 */
	public function __construct( $dependencies = array(), $pluginName, $showAdminMessages = true ) {
		$this->pluginDependencies = $dependencies;
		$this->pluginName = $pluginName;
		$this->showAdminMessages = $showAdminMessages;
		$this->start();
	}

	private function start() {
		//Get Missing Plugins
		$this->missingPlugins = $this->findMissingPlugins($this->pluginDependencies);

		//Show plugin missing Admin messages
		if ( $this->showAdminMessages ) {
			if ( count($this->missingPlugins) > 0 ) {
				add_action('admin_notices', array($this, 'showPluginDependenciesAdminMessages'));
			}
		}
	}

	function showAdminMessage( $message, $errormsg = false ) {
		if ( $errormsg ) {
			echo '<div id="message" class="error">';
		} else {
			echo '<div id="message" class="updated fade">';
		}
		echo "<p><strong>$message</strong></p></div>";
	}

	public function showPluginDependenciesAdminMessages() {
		$missingPlugins = $this->missingPlugins;
		foreach ( $missingPlugins as $key => $value ) {
			$pluginNameStr = isset($value['name']) ? $value['name'] : $value['class'];
			if ( isset($value['url']) ) {
				$pluginNameStr = '<a href="' . $value['url'] . '">' . $pluginNameStr . '</a>';
			}
			$type = isset($value['type']) ? $value['type'] : 'plugin';
			$this->showAdminMessage($this->pluginName . ' needs this ' . $type . ' to work: ' . $pluginNameStr, true);
		}
	}

	private function findMissingPlugins( $dependencies ) {
		$missingPlugins = array();

		//if(!is_admin())
		if ( did_action('my_custom_action') === 0 )
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		foreach ( $dependencies as $value ) {
			$testMethods = $relation = $value['test_methods'];
			$relation = isset($testMethods['relation']) ? $testMethods['relation'] : 'or';

			$failedTests = 0;
			$possibleErrors = 0;
			foreach ( $testMethods as $testMethod ) {
				if ( is_array($testMethod) ) {

					@$testMethodIsPluginActive = $testMethod['is_plugin_active'];
					if ( isset($testMethodIsPluginActive) ) {
						$possibleErrors++;
						if ( !is_plugin_active($testMethodIsPluginActive) ) {
							$failedTests++;
						}
					}

					@$testMethodClassExists = $testMethod['class_exists'];
					if ( isset($testMethodClassExists) ) {
						$possibleErrors++;
						if ( !class_exists($testMethodClassExists) ) {
							$failedTests++;
						}
					}

					@$testMethodFunctionExists = $testMethod['function_exists'];
					if ( isset($testMethodFunctionExists) ) {
						$possibleErrors++;
						if ( !function_exists($testMethodFunctionExists) ) {
							$failedTests++;
						}
					}
				}
			}

			if ( strtoupper($relation) == 'OR' ) {
				if ( $failedTests >= 1 ) {
					$missingPlugins[] = $value;
				}
			} else if ( strtoupper($relation) == 'AND' ) {
				if ( $failedTests == $possibleErrors ) {
					$missingPlugins[] = $value;
				}
			}
		}
		return $missingPlugins;
	}

	public function getPluginDependencies() {
		return $this->pluginDependencies;
	}

	public function getMissingPlugins() {
		return $this->missingPlugins;
	}

}
