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
 $this->getLogger()->info("v3.1.0");
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
		$name = $player->getName();
		$buttons[] = [ 
        'text' => "§l§4プレイヤーをUBANする", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ]; //0
        $buttons[] = [ 
        'text' => "§l§6プレイヤーを警告(Warn)する", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ]; //1
        $buttons[] = [ 
        'text' => "§l§eプレイヤーをtxtでUBANする §7§l[§c✕§7]", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ]; //2
        $buttons[] = [ 
        'text' => "§l§3プレイヤーのUBANを解除する §7§l[§c✕§7]", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ]; //3
        $buttons[] = [ 
        'text' => "§l§5プレイヤーのWarnを解除する §7§l[§c✕§7]", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ]; //4
        $this->sendForm($player,"§l§7UBANS-ADDON","\n",$buttons,10);
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
            case 10:
            if($data == 0){//uban
            $players = Server::getInstance()->getOnlinePlayers();
				foreach($players as $player1){
		$name = $player1->getName();
		$buttons[] = [ 
        'text' => "{$name}", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ];
        }
        $this->sendForm($player,"§l§7UBANS-ADDON","BANするプレイヤーを選択してください\n\n\n",$buttons,11);
        $this->info[$name] = "form";
		break;
        
        }elseif($data == 1){//warn
        $players = Server::getInstance()->getOnlinePlayers();
				foreach($players as $player1){
		$name = $player1->getName();
		$buttons[] = [ 
        'text' => "{$name}", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ];
        }
        $this->sendForm($player,"§l§7UBANS-ADDON","警告(Warn)するプレイヤーを選択してください\n\n\n",$buttons,12);
        $this->info[$name] = "form";
		break;
        
        }elseif($data == 2){//txtban
        $data = [
				
				"type" => "custom_form",

				"title" => "§l§7UBANS-ADDON",

				"content" => [

					[

						"type" => "label",

						"text" => "§c§lBANしない情報は「§fnull§c」と入力してください！"

					],

					[

						"type" => "input",

						"text" => "§lPlayer_Name",

						"placeholder" => "名前"

					], //1
					
					[
					
						"type" => "input",

						"text" => "§lIP_Address",

						"placeholder" => "ipアドレス"

					], //2
					
					[

						"type" => "input",

						"text" => "§lHostID",

						"placeholder" => "ホストID"

					], //3
					
					[

						"type" => "input",

						"text" => "§lClientID",

						"placeholder" => "クライアントID"

					], //4
					
					[

						"type" => "input",

						"text" => "§lUniqueID",

						"placeholder" => "ユニークID"

					],
					
					[

						"type" => "input",

						"text" => "§lRawID",

						"placeholder" => "ローID"

					],
					
					[

						"type" => "input",

						"text" => "§lReason",

						"placeholder" => "理由"

					]
					


				]

			];

			$this->createWindow($player, $data, 13);
			break;
			
			
			}elseif($data == 3){
			$names = $this->UBans->GetAllBannedPlayerName();
			foreach($names as $key => $value){
		    $buttons[] = [ 
             'text' => "{$key}", 
             'image' => [ 'type' => 'path', 'data' => "" ] 
            ];
            $this->sendForm($player,"§l§7UBANS-ADDON","BANを解除するプレイヤーを選択してください\n\n\n",$buttons,25);
			break;
			}
			}elseif($data == 4){
			$names = $this->UBans->GetAllWarnPlayerName();
			foreach($names as $key => $value){
		     $buttons[] = [ 
             'text' => "{$key}", 
             'image' => [ 'type' => 'path', 'data' => "" ] 
            ];
            }
            $this->sendForm($player,"§l§7UBANS-ADDON","Warnを解除するプレイヤーを選択してください\n\n\n",$buttons,810);
			break;
			}

            case 11:
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

						"placeholder" => "理由を入力してください",

						"default" => ""

					]

				]

			];

			$this->createWindow($player, $data, 20);

				}
			}
		}

              break;
              
              
              case 20:
              $this->s = Server::getInstance();
              $name = $result[1];
              $reason = $result[2];
              $name1 = $result[1];
              $player = $this->s->getPlayer($name);
              $this->UBans->UBan($name ,$reason ,$name1);
			  $this->getServer()->broadcastMessage("§a{$name1}§fが§c{$name}§fを§eUBan§fしました\n".
			  									   "§e理由 §f:§6 $reason");
			  $player->kick("§cサーバーとの接続が切断されました \n§6理由 §f: §6$reason ", true);
			  break;
			  
			  case 12:
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

						"text" => "§l理由を記入してください"

					],
					
					[

						"type" => "input",

						"text" => "§l§b警告するプレイヤー",
						
						"default" => "{$name}"

					],

					[

						"type" => "input",

						"text" => "§l§6理由",

						"placeholder" => "理由を入力してください",

						"default" => ""

					]

				]

			];

			$this->createWindow($player, $data, 21678923);

				}
			}
		}

              break;
		
		
		
		 case 21678923:
		 $this->s = Server::getInstance();
              $name1 = $player->getName();
              $reason = $result[2];
              $name = $result[1];
              $player1 = $this->s->getPlayer($name);
              $this->UBans->Warn($name ,$reason ,$name1);
			  $this->getServer()->broadcastMessage("§a{$name1}§fが§c{$name}§fを§eWarn§fしました\n".
												   "§e理由 §f:§6 $reason");
			  $player1->sendMessage("§cあなたは管理者から危険人物に認定されました\n§c理由 §f:§6$reason");
			  $this->UBans->setDanger($player1);
			  break;

 		case 13:
 		$name   = $result[1];
 		$ip     = $result[2];
 		$host   = $result[3];
 		$cid    = $result[4];
 		$uuid   = $result[5];
 		$rawid  = $result[6];
 		$reason = $result[7];
 		
 		$sender_name = $player->getName();
 		$this->UBans->UBanByText($name, $ip, $host, $cid, $uuid, $rawid, $reason, $sender_name);
 		$player->sendMessage("§e{$name}をテキストでUBANしました。");
 		$player->sendMessage("§6IPアドレス: {$ip}");
 		$player->sendMessage("§6ホスト: {$host}");
 		$player->sendMessage("§6クライアントID: {$cid}");
 		$player->sendMessage("§6ユニークID: {$uuid}");
 		$player->sendMessage("§6ローID: {$rawid}");
 		$player->sendMessage("§6理由: {$reason}");
 			break;
 			
 			
 			
			
			
		case 25://ban解除
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
		$this->UBans->unUBan($name);
		$player->sendMessage("{$name}のUBANを解除しました");
		break;
		}
	}
}
		case 810:
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
		$this->UBans->unWarn($name);
		$this->UBans->setDefaultStatus($target);
		$player->sendMessage("{$name}のWarnを解除しました");
		break;
						}
					}
				}
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
