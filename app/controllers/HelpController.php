<?php

/*
  +------------------------------------------------------------------------+
  | Phosphorum                                                             |
  +------------------------------------------------------------------------+
  | Copyright (c) 2013-2014 Phalcon Team and contributors                  |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
*/

namespace Phosphorum\Controllers;

use Phosphorum\Models\Users,
	Phosphorum\Models\Posts,
	Phosphorum\Models\PostsReplies,
	Phalcon\Http\Response,
	Phalcon\Mvc\Controller;

class HelpController extends Controller
{

	public function initialize()
	{
		$this->tag->setTitle('도움말');
		$this->view->setTemplateBefore(array('discussions'));
	}

	public function indexAction()
	{

	}

	public function karmaAction()
	{

	}

	public function markdownAction()
	{

	}

	public function votingAction()
	{

	}

	public function moderatorsAction()
	{

	}

}
