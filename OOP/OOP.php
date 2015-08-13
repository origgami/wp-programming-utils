<?php

namespace OriggamiWpProgrammingUtils\OOP;

/**
 * Description of OOP
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */
class OOP {
	//put your code here
	
	public function __construct() {
		
	}
	
	public function loadUsefulClasses(){
		require dirname(__FILE__) . '/PostType/OriggamiPostType.php';
		require dirname(__FILE__) . '/Taxonomy/OriggamiTax.php';
	}
}
