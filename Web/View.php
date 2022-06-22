<?php
namespace FakeSudo\Pluto\Web;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View{
    private static $twig;
    public static function render($view,$arguments){
        echo self::$twig->render($view.'.html.twig', $arguments);
    }
    public static function load($path){
        $loader = new FilesystemLoader($path);
        self::$twig = new Environment($loader);
    }
}