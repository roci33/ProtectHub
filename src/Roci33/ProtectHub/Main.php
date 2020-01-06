<?php
declare(strict_types=1);

namespace Roci33\ProtectHub;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->saveDefaultConfig();

        $this->getServer()->loadLevel((string)$this->getConfig()->get("Hub"));
    }
}
