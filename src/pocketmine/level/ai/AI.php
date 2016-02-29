<?php
namespace pocketmine\level\ai;

use pocketmine\Server;
use pocketmine\level\Level;

use pocketmine\entity\Entity;

class AI{
	private $level;
	private $levelId;
	private $mobs=[];
	
	public function __construct(Level $level){
		$this->level = $level;
		$this->levelId = $level->getId();
	}
	
	public function getLevel(){
		return $this->level;
	}
	
	public function getServer(){
		return $this->level->getServer();
	}
	
	public function registerAI(Entity $entity){
		$this->mobs[$entity->getId()] = $entity->getName();
	}
	
	public function unregisterAI(Entity $entity){
		unset($this->mobs[$entity->getId()]);
	}
	
	public function tickMobs(){
		//print_r($this->getLevel()->getChunks());
		foreach($this->mobs as $mobId => $mobType){
			$this->getServer()->getScheduler()->scheduleAsyncTask(new MoveCalculaterTask($this->getBlocksAround(), $this->levelId, $mobId, $mobType));
		}
		//echo "Level ".$this->getLevel()->getName()." Receive Tick Request\n";
	}
	
	public function moveCalculationCallback($result){
		$entity = $this->getServer()->getLevel($this->levelId)->getEntity($result['id']);
		$this->setPosition($entity->temporalVector->setComponents($result['x'], $result['y'], $result['z']));
	}
	
}