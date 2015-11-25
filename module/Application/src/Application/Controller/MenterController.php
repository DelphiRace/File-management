<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use System_APService\clsSystem;

class MenterController extends AbstractActionController
{
    public function loginAction()
    {
		$VTs = new clsSystem;
		//先初始化
        $VTs->initialization();
		try{
		//-----------BI開始------------
			//設定資訊陣列
			$uidInfo = array();
			//資訊狀態
			$uidInfo["status"] = false;
			
			//檢測是否有傳入帳號與密碼
			if(!empty($_POST) and !empty($_POST["userAc"]) and !empty($_POST["userPw"])){
				$userAc = $_POST["userAc"];
				$userPw = $_POST["userPw"];
				//登入驗證步驟
				//1.檢驗帳號與密碼(若錯誤回傳錯誤)
				$strSQL = "select * from acl_user where userAc = '".$userAc."' and userPw = md5('".$userPw."')";
				$data = $VTs->QueryData($strSQL);
				
				//2.通過檢驗後，回傳登入Code與狀態
				if(!empty($data)){
					
					$uuid = $data[0]["uuid"];
					$uidInfo["uuid"] = $uuid;
	                $uidInfo["userAc"] = $userAc;
	                $uidInfo["name"] = $data[0]["userName"];
	                $uidInfo["status"] = true;
				}else{ //2-1. 未通過驗證
					$uidInfo["error"] = 'The Accound is not Sing up!';
					$uidInfo["code"] = '2';
				}	
				//3.寫入LOG
				//$VTs->saveLog('loginAction','system','creatToken',$uidInfo["status"]);
			}else{//1-1 帳號密碼為空，回傳狀態
				$uidInfo["error"] = 'Accound or Password is Empty';
				$uidInfo["code"] = '1';
			}		
			$this->viewContnet["pageContent"] = $VTs->Data2Json($uidInfo);
		//-----------BI結束------------ 
		}catch(Exception $error){
			//依據Controller, Action補上對應位置, $error->getMessage()為固定部份
			$VTs->WriteLog("IndexController", "indexAction", $error->getMessage());
		}
		//關閉資料庫連線
		$VTs->DBClose();
		//釋放
		$VTs=null;
		
		return new ViewModel($this->viewContnet);
		
    }

    public function setloginAction()
    {
		$VTs = new clsSystem;
		//先初始化
        $VTs->initialization();
		try{
		//-----------BI開始------------
			//設定資訊陣列
			$action = array();
			//資訊狀態
			$action["status"] = false;
			
			//檢測是否有傳入帳號與密碼
			if(!empty($_POST["uuid"]) && !empty($_POST["userAc"])){
				$_SESSION["uuid"] = $_POST["uuid"];
				$_SESSION["userAc"] = $_POST["userAc"];
				$_SESSION["userName"] = $_POST["name"];
			}else{//1-1 帳號密碼為空，回傳狀態
				$action["error"] = 'Accound Info is Empty';
				$action["code"] = '1';
			}		
			$this->viewContnet["pageContent"] = $VTs->Data2Json($action);
		//-----------BI結束------------ 
		}catch(Exception $error){
			//依據Controller, Action補上對應位置, $error->getMessage()為固定部份
			$VTs->WriteLog("IndexController", "indexAction", $error->getMessage());
		}
		//關閉資料庫連線
		$VTs->DBClose();
		//釋放
		$VTs=null;
		
		return new ViewModel($this->viewContnet);
		
    }

    public function logoutAction()
    {
		@session_start();
		@session_destroy();
		return new ViewModel();
		
    }
}
