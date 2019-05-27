<?php

class Main extends Controller {

    /*
     * http://localhost/
     */
    function Index () {
        
        if (!isset($_SESSION['login'])) {

            header('Location: /login');

        } else {

            header('Location: /dashboard');
            
        }
        
    }

    /*
     * http://localhost/anothermainpage
     */
    function anotherMainPage () {
        echo 'Works!';
    }
    
    public static function r404 () {
    	header('HTTP/1.0 404 Not Found');
    }


}

?>
