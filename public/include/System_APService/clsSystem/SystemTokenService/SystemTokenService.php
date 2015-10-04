<?php		
	namespace SystemTokenService;
	
	
	class clsToken {
		public $Login = array();
	#creat
		//創建Login Code & Token
		public function CreatLoginCodeAndToken($userID){
			//創建Login Code
			$this->CreatLoginCode($userID);
			//創建Access Token
			$this->CreatAccessToken($userID);
			return $this -> Login;
		}
		
		//創建Login Code
		private function CreatLoginCode($userID){
			$code = md5($userID.time().date("Y-m-d H:i:s"));
			$this -> Login["Login_Code"] = $code;
		}
		
		//創建Access Token
		private function CreatAccessToken($userID){
			$tokenStr = md5($userID.time());
			$this -> Login["Access_Token"] = $tokenStr;
		}
	#creat end
		
	}
?>