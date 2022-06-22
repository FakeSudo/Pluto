<?php
namespace FakeSudo\Pluto\Web;

use FakeSudo\Pluto\Kernel;

class Client {

    private $rootPath;

    private $Router;

    public function __construct($rootPath) {
        $this->rootPath = str_replace('/public','',$rootPath);
        $this->Router = new \Bramus\Router\Router();
    }
    
    public function load(){
        View::load($this->rootPath."/views/");
        Kernel::loadORM($this->rootPath);
        $this->Router();
    }

    private function Router(){
        $Route = $this->Router;
        require_once($this->rootPath.'/routes/web.php');
        $Route->run();
    }
}