<?php
declare(strict_types=1);

namespace Roci33\ProtectHub;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerExhaustEvent;

class EventListener implements Listener {

    /** @var Main $plugin */
    public $plugin;

    /**
     * EventListener constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $player->teleport($this->plugin->getServer()->getLevelByName((string)$this->plugin->getConfig()->get("Hub"))->getSpawnLocation());
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $player->teleport($this->plugin->getServer()->getLevelByName((string)$this->plugin->getConfig()->get("Hub"))->getSpawnLocation());
        if ($this->plugin->getConfig()->get("Void") === "off") {
            if (!$event->getPlayer()->hasPermission("primelobby.bypass")) {
                if ($event instanceof EntityDamageEvent) {
                    if ($event->getCause() === EntityDamageEvent::CAUSE_VOID) {
                        $player->teleport($this->plugin->getServer()->getLevelByName((string)$this->plugin->getConfig()->get("Hub"))->getSpawnLocation());
                    }
                }
            }
        }
    }
    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event): void {
        if (in_array($event->getPlayer()->getLevel()->getName(), [$this->plugin->getConfig()->get("Hub")])) {
            if ($this->plugin->getConfig()->get("Place block") === "off") {
                if (!$event->getPlayer()->hasPermission("primelobby.bypass")) {
                    $event->setCancelled();
                }
            }
        }
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event): void {
        if ($this->plugin->getConfig()->get("Break Block") === "off") {
            if (in_array($event->getPlayer()->getLevel()->getName(), [$this->plugin->getConfig()->get("Hub")])) {
                if (!$event->getPlayer()->hasPermission("primelobby.bypass")) {
                    $event->setCancelled();
                }
            }
        }
    }

    /**
     * @param PlayerBucketEmptyEvent $event
     */
    public function onBucket(PlayerBucketEmptyEvent $event): void {
        if ($this->plugin->getConfig()->get("Place water/lava") === "off") {
            if (in_array($event->getPlayer()->getLevel()->getName(), [$this->plugin->getConfig()->get("Hub")])) {
                if (!$event->getPlayer()->hasPermission("primelobby.bypass")) {
                    $event->setCancelled();
                }
            }
        }
    }

    public function onHunger(PlayerExhaustEvent $event): void {
        if (in_array($event->getPlayer()->getLevel()->getName(), [$this->plugin->getConfig()->get("Hub")])) {
            if ($this->plugin->getConfig()->get("Hunger") === "off") {
                    $event->setCancelled();
                }
            }
        }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event): void {
        if ($this->plugin->getConfig()->get("PvP") === "off") {
            if (in_array($event->getEntity()->getLevel()->getName(), [$this->plugin->getConfig()->get("Hub")])) {
                    if ($event->getEntity() instanceof Player) {
                        $event->setCancelled();
                    }
                }
            }
        }
    }