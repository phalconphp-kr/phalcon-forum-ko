<?php

namespace Phosphorum\Controllers;

class IndexController extends \Phalcon\Mvc\Controller
{

	public function indexAction()
	{				
		$this->flashSession->error($this->escaper->escapeHtml($this->router->getRewriteUri()).' 페이지를 찾지 못하였습니다');
		return $this->response->redirect('discussions');
	}
}
