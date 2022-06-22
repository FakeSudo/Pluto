<?php

namespace FakeSudo\Pluto\Telegram;

use FakeSudo\Pluto\Kernel;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class Client
{

    private Update $Update;

    private Telegram $Telegram;

    private Router $Routes;

    private $rootPath;

    public function __construct($rootPath)
    {
        Kernel::loadEnv($rootPath);
        $this->rootPath = str_replace('/public','',$rootPath);
        $this->Telegram = new Telegram($_ENV['TELEGRAM_BOT_API_KEY'], $_ENV['TELEGRAM_BOT_USERNAME']);
        $this->Telegram->handle();

        $Input = json_decode(Request::getInput(), true);
        $this->Update = new Update($Input, $_ENV['TELEGRAM_BOT_USERNAME']);

        $this->Routes = new Router($this->Update);

        Kernel::loadORM($rootPath);
    }

    private function getRouter(){
        return $this->Routes;
    }

    public function load(){
        $Route = $this->getRouter();
        require_once $this->rootPath . '/routes/Telegram.php';
    }
}
