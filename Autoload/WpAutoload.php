<?php

namespace OriggamiWpProgrammingUtils\Autoload;

require dirname( __FILE__ ) . '/WpAutoloadFunction.php';

/**
 * Description of WpAutoload
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */
class WpAutoload {

    //put your code here

    private $folders;

    public function autoloadBasedOnNamespace( $baseFolder = null ) {
        $folders = $this->getFolders();
        if ( !$folders ) {
            $folders = array();
            $this->setFolders( $folders );
        }

        if ( !$baseFolder ) {
            $baseFolder = get_stylesheet_directory();
        }

        if ( array_search( $baseFolder, $folders ) === false ) {
            new WpAutoloadFunction( $baseFolder );
        }
    }

    function getFolders() {
        return $this->folders;
    }

    function setFolders( $folders ) {
        $this->folders = $folders;
    }

}
