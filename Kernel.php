<?php
namespace FakeSudo\Pluto;

use FakeSudo\Pluto\Telegram\Client;
use FakeSudo\Pluto\Web\Client as WebClient;
use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

class Kernel {
    
    public static function Telegram($rootPath){
        return new Client($rootPath);
    }
    public static  function WebClient($rootPath){
        return new WebClient($rootPath);
    }
    public static function loadEnv($rootPath){
        Dotenv::createImmutable(str_replace(DIRECTORY_SEPARATOR.'public','',$rootPath))->load();
    }
    public static function loadORM($rootPath){
        self::loadEnv($rootPath);
        if(!is_null($_ENV['DB_DRIVER'])){
            $capsule = new Capsule;

            $capsule->addConnection([
                "driver" => $_ENV['DB_DRIVER'],
                "host" =>$_ENV['DB_HOST'],
                "database" => $_ENV['DB_NAME'],
                "username" => $_ENV['DB_USER'],
                "password" => $_ENV['DB_PASSWORD'],
                "port" => $_ENV['DB_PORT'],
            ]);
    
            $capsule->setAsGlobal();
    
            $capsule->bootEloquent();
        }
    }
}