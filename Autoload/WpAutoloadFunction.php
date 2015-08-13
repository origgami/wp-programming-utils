<?php

namespace OriggamiWpProgrammingUtils\Autoload;

/**
 * Description of WpAutoloadFunction
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */
class WpAutoloadFunction {

    private $folder;

    public function __construct( $folder ) {
        $this->setFolder( $folder );
        spl_autoload_register( array( $this, 'autoloadFunction' ) );
    }
    
    private function requireFile($filePath){     
        if ( file_exists( $filePath ) ) {            
            require $filePath;
            return true;
        }else{
            return false;
        }
    }

    public function autoloadFunction( $class ) {
        if ( !class_exists( $class ) ) {
            $filePath = $this->getFolder() . DIRECTORY_SEPARATOR . $class . '.php';
			$filePath = str_replace('\\', '/', $filePath);
            if(!$this->requireFile($filePath)){
                //$filePath = strtolower($filePath);
                //$this->requireFile($filePath);
            }
        }
    }

    function getFolder() {
        return $this->folder;
    }

    function setFolder( $folder ) {
        $this->folder = $folder;
    }

}
