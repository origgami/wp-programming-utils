<?php

/**
 * 
 * @return \OriggamiWpProgrammingUtils\OriggamiWpProgrammingUtils
 */
if ( !function_exists('oWpUtils') ) {

	function oWpUtils() {
		$programmingTools = \OriggamiWpProgrammingUtils\OriggamiWpProgrammingUtils::getInstance();
		return $programmingTools;
	}

}