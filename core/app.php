<?php

/*
 *   Date: 2017-06-01
 * Author: Dawid Yerginyan
 */

class App {

    private $config = [];

    public $db;

    function __construct () {

        define("URI", strtok($_SERVER['REQUEST_URI'], '?'));
        define("ROOT", $_SERVER['DOCUMENT_ROOT']);

    }

    function autoload () {

        spl_autoload_register(function ($class) {

            $class = strtolower($class);
            if (file_exists(ROOT . '/core/classes/' . $class . '.php')) {

                require_once ROOT . '/core/classes/' . $class . '.php';

            } else if (file_exists(ROOT . '/core/helpers/' . $class . '.php')) {

                require_once ROOT . '/core/helpers/' . $class . '.php';

            }

        });

    }

    function config () {

        $this->require('/core/config/session.php');
        $this->require('/core/config/database.php');      

        try {

            $this->db = new PDO('mysql:host=' . $this->config['database']['hostname'] . ';dbname=' . $this->config['database']['dbname'],
                                $this->config['database']['username'], 
                                $this->config['database']['password']);

            $this->db->query('SET NAMES utf8');
            $this->db->query('SET CHARACTER_SET utf8_unicode_ci');
            
            // TODO: Remove for production
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            echo 'Błąd połączenia: ' . $e->getMessage();

        }

    }

    function require ($path) {

        require ROOT . $path;

    }

    function start () {

        $route = explode('/', URI);

        $route[1] = strtolower(str_replace('-','_',$route[1]));

		if(!$route[1])
        	$route[1] = "main";
        	
        if (file_exists(ROOT . '/app/controllers/' . $route[1] . '.php')) {
            $this->require('/app/controllers/' . $route[1] . '.php');
            $controller = new $route[1]();
        } else {
            $this->require('/app/controllers/main.php');
            Main::r404();
        }

    }
    
}

?>
