<?php
namespace FakeSudo\Pluto\Web;

use FakeSudo\Pluto\Kernel;

class Client {

    private $rootPath;

    private $Router;

    public function __construct($rootPath) {
        $this->rootPath = str_replace(DIRECTORY_SEPARATOR . 'public','',$rootPath);
        $this->Router = new \Bramus\Router\Router();
    }
    
    public function load(){
        View::load($this->rootPath.DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR);
        Kernel::loadORM($this->rootPath);
        $this->Router();
    }

    private function Router(){
        $Route = $this->Router;
        require_once($this->rootPath.DIRECTORY_SEPARATOR.'routes'.DIRECTORY_SEPARATOR.'web.php');
        $Route->run();
    }
}