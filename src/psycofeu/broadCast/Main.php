<?php

namespace psycofeu\broadCast;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase implements Listener
{
    use SingletonTrait;
    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getLogger()->notice("Plugin enable");
        $this->getScheduler()->scheduleRepeatingTask(new class extends Task {
            public function onRun(): void{
                $config = Main::getInstance()->getConfig();
                Server::getInstance()->broadcastMessage($config->get("prefix") . str_replace(["{line}", "{online}", "{max_online}"], ["\n", count(Server::getInstance()->getOnlinePlayers()) ?? 0, Server::getInstance()->getMaxPlayers() ?? 0], $config->get("broadcast_message")[array_rand($config->get("broadcast_message"))]));
            }
        }, $this->getConfig()->get("broadcast_time")*20*60);
    }
}