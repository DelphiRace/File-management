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

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
		@session_start();
		$HeadTitle = $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle');
		
		if(empty($_SESSION)){
			//倒網址
			$urlProtocol = ($_SERVER['HTTPS']) ? "https://":"http://";
			header("location: http://127.0.0.1:120?redirect_url=".$urlProtocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/auth_back.php");
			exit();
		}else{
			$isLoginContent = $this->getPageContent('index','after_login');
			$isLoginContent = str_replace("@@userName@@",$_SESSION["userName"],$isLoginContent);
			$this->viewContnet['pageContent']= $isLoginContent;
		}
        return new ViewModel($this->viewContnet);
    }
	
	private function getPageContent($pageType,$pageName){
		$pagePath = dirname(__DIR__) . "\\..\\..\\..\\..\\public\\include\\pageContent\\".$pageType."\\".$pageName.".html";
		$pageContent = '';
		if(file_exists($pagePath)){
			$pageContent = file_get_contents($pagePath);
		}
		return $pageContent;
	}
}
