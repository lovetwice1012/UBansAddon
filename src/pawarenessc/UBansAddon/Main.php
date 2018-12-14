<?php



namespace pawarenessc\UBansAddon;



use pocketmine\event\Listener;

use pocketmine\plugin\PluginBase;


use pocketmine\Player;
use pocketmine\Server;



use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;



class Main extends pluginBase implements Listener{









public function onEnable() {

 $this->getLogger()->info("=========================");
 $this->getLogger()->info("UBansAddonを読み込みました");
 $this->getLogger()->info("v1");
 $this->getLogger()->info("=========================");

 $this->getServer()->getPluginManager()->registerEvents($this, $this);
 $this->UBans = Server::getInstance()->getPluginManager()->getPlugin("UBans");


}
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) :bool{

 switch ($command->getName()){
  case "ubanad";
 if ($sender->isOp()) {
$this->ubanUI($sender);
}else{
$sender->sendMessage("§4権限がありません");
}
return true;

  break;
}
}

   
   public function ubanUI($player) {
    
        $players = Server::getInstance()->getOnlinePlayers();
				foreach($players as $player1){
		$name = $player1->getName();
		$buttons[] = [ 
        'text' => "{$name}", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ];
        }
        $this->sendForm($player,"§l§7UBAN","BANするプレイヤーを選択してください\n\n\n",$buttons,7);
        $this->info[$name] = "form";
		return true;
  
  }
  public function onPrecessing(DataPacketReceiveEvent $event){



  $player = $event->getPlayer();

  $pk = $event->getPacket();

  $name = $player->getName();

    if($pk->getName() == "ModalFormResponsePacket"){

      $data = $pk->formData;

      $result = json_decode($data);

          if($data == "null\n"){

      }else{
	    
	    switch($pk->formId){
            case 7:
            $players = Server::getInstance()->getOnlinePlayers();
		$result = $data[0];

 		if($result === null){

			return true;

		}

			$c = 0;

			 foreach ($players as $p){

				if($result == $c){

					$target = $p->getPlayer();	

					if($target instanceof Player){
		$name = $target->getName();
		$data = [

				"type" => "custom_form",

				"title" => "§6UBANS",

				"content" => [

					[

						"type" => "label",

						"text" => "§lプレイヤーの名前を記入してください。"

					],
					
					[

						"type" => "input",

						"text" => "§l§bBANするプレイヤー",
						
						"default" => "{$name}"

					],

					[

						"type" => "input",

						"text" => "§l§6理由",

						"placeholder" => "名前を入力してください",

						"default" => ""

					]

				]

			];

			$this->createWindow($player, $data, 9);

				}
			}
		}

              break;
              
              
              case 9:
              $this->s = Server::getInstance();
              $name = $result[1];
              $reason = $result[2];
              $name1 = $result[1];
              $player = $this->s->getPlayer($name);
              $this->UBans->UBan($name ,$reason ,$name1);
			  $this->getServer()->broadcastMessage("§a{$name1}§fが§c{$name}§fを§eUBan§fしました\n".
			  									   "§e理由 §f:§6 $reason");
			  $player->kick("§cサーバーとの接続が切断されました \n§6理由 §f: §6$reason ", true);


 			}
		}
	}
}
	public function createWindow(Player $player, $data, int $id){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
		$player->dataPacket($pk);
	}
	
	
	public function sendForm(Player $player, $title, $come, $buttons, $id) {
  $pk = new ModalFormRequestPacket(); 
  $pk->formId = $id;
  $this->pdata[$pk->formId] = $player;
  $data = [ 
  'type'    => 'form', 
  'title'   => $title, 
  'content' => $come, 
  'buttons' => $buttons
  ]; 
  $pk->formData = json_encode( $data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE );
  $player->dataPacket($pk);
  $this->lastFormData[$player->getName()] = $data;
  }
  
  
 }
