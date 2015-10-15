<?php


	class LOLAPI{
        
        public $key = "a886764e-c570-4037-a360-a32b3dda9f02";
		
		
		public function getSummonerInfo($nick,$server){
            
        
            
            $url = 'https://na.api.pvp.net/api/lol/'.$server.'/v1.4/summoner/by-name/'.$nick.'?api_key='.$this->key;
            
            $url = str_replace(" ", "%20", $url);
			
			$fullText = file_get_contents($url);
			$data = array();
			
			preg_match('#"id"(.*)#',$fullText,$var);
			
			
			$var[0] = str_replace("\"", "", $var[0]);
			$var[0] = str_replace("{", "", $var[0]);
			$var[0] = str_replace("}", "", $var[0]);
				
			$atribs = explode(',', $var[0]);
				
			foreach ($atribs as $atrib) {
				$field = explode(':', $atrib);
				$data[$field[0]] = $field[1];
			}
			
			return $data;	
		}
                
        public function getChampionName($id){
            $id = $this->getCurrentGame($id,"na");
            $id = $id['championId'];
            
            if (!$var = file_get_contents('https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion/'.$id.'?api_key='.$this-> key)) {
                  $error = error_get_last();
                  echo "HTTP request failed. Error was: " . $error['message'];
            } 
            $data = array();
            
            
            $var = str_replace("\"", "", $var);
            $var = str_replace("{", "", $var);
            $var = str_replace("}", "", $var);
            $var = str_replace("[", "", $var);
            $var = str_replace("]", "", $var);
            
            $atribs = explode(',', $var);

            
            foreach ($atribs as $atrib) {  
                $field = explode(':', $atrib);
                $data[$field[0]] = $field[1];
            }
            
            return $data;
            
           
        }
        
        public function getCurrentGame($id){
            
            $id = $this->getSummonerInfo($id,"na");
            $id = $id['id'];
            $var = @file_get_contents('https://na.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/NA1/'.$id.'?api_key='.$this-> key);
            
            $data = array();
            if(!$var){
                return false;
            }
            

            
            $var = str_replace("\"", "", $var);
            $var = str_replace("{", "", $var);
            $var = str_replace("}", "", $var);
            $var = str_replace("[", "", $var);
            $var = str_replace("]", "", $var);
            
            
           
            $atribs = explode(',', $var);

            
            foreach ($atribs as $atrib) {  
                $field = explode(':', $atrib);
                $data[$field[0]] = $field[1];
            }

            return $data;
        }
		
		function getLastGameResult($id){
		
			$key = "fd9f3698-3b77-4730-a0be-0e9a15f6b22e";
			
			$var = file_get_contents('https://prod.api.pvp.net/api/lol/na/v1.3/game/by-summoner/'.$id.'/recent?api_key='.$key);
			$data = array();
			
			
			$var = str_replace("\"", "", $var);
			$var = str_replace("{", "", $var);
			$var = str_replace("}", "", $var);
			
				
			$atribs = explode(',', $var);
				
			foreach ($atribs as $atrib) {
				$field = explode(':', $atrib);
				
				if($field[0]=="win"){
					$data[$field[0]] = $field[1];
					break;
				}
			}
			
			return $data;	
		
		
		}
		
		

	
	}
    
    
    $LOLAPI = new LOLAPI();
    
    $data = $LOLAPI->getChampionName("HUE");
    
    if($data){
        echo $data['name'];
    }
    else {
           echo "Not in game";
    }
    

    echo $data['name'];
?>
