<?php

namespace Phosphorum\Controllers;

use \Phalcon\Mvc\Controller;

/**
 * Class IndexController
 *
 * @package Phosphorum\Controllers
 */
class IndexController extends Controller
{

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function indexAction()
    {
        $this->view->disable();
        $this->flashSession->error('Page not found');

        return $this->response->redirect();
    }
}
