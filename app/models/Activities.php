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

namespace Phosphorum\Models;

use Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Behavior\Timestampable;

class Activities extends Model
{

	public $id;

	public $users_id;

	public $type;

	public $posts_id;

	public $created_at;

	public function initialize()
	{
		$this->belongsTo('users_id', 'Phosphorum\Models\Users', 'id', array(
			'alias' => 'user',
			'reusable' => true
		));

		$this->belongsTo('posts_id', 'Phosphorum\Models\Posts', 'id', array(
			'alias' => 'post',
			'reusable' => true
		));

		$this->addBehavior(new Timestampable(array(
			'beforeCreate' => array(
				'field' => 'created_at'
			)
		)));
	}

	public function getHumanCreatedAt()
	{
		$diff = time() - $this->created_at;
		if ($diff > (86400 * 30)) {
			return date('M \'y', $this->created_at);
		} else {
			if ($diff > 86400) {
				return ((int) ($diff / 86400)) . '일 전에';
			} else {
				if ($diff > 3600) {
					return ((int) ($diff / 3600)) . '시간 전에';
				} else {
					return ((int) ($diff / 60)) . '분 전에';
				}
			}
		}
	}

}