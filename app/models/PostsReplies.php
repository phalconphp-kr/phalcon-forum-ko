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

class PostsReplies extends Model
{

	public $id;

	public $posts_id;

	public $users_id;

	public $in_reply_to_id;

	public $content;

	public $created_at;

	public $modified_at;

	public $edited_at;

	public $votes_up;

	public $votes_down;

	public $accepted;

	public function initialize()
	{
		$this->belongsTo('posts_id', 'Phosphorum\Models\Posts', 'id', array(
			'alias' => 'post',
			'reusable' => true
		));

		$this->belongsTo('in_reply_to_id', 'Phosphorum\Models\PostsReplies', 'id', array(
			'alias' => 'postReplyTo',
			'reusable' => true
		));

		$this->belongsTo('users_id', 'Phosphorum\Models\Users', 'id', array(
			'alias' => 'user',
			'reusable' => true
		));

		$this->addBehavior(new Timestampable(array(
			'beforeCreate' => array(
				'field' => 'created_at'
			),
			'beforeUpdate' => array(
				'field' => 'modified_at'
			)
        )));
	}

	public function beforeCreate()
	{
		if ($this->in_reply_to_id > 0) {
			$postReplyTo = self::findFirstById($this->in_reply_to_id);
			if (!$postReplyTo) {
				$this->in_reply_to_id = 0;
			} else {
				if ($postReplyTo->posts_id != $this->posts_id) {
					$this->in_reply_to_id = 0;
				}
			}
		}
		$this->accepted = 'N';
	}

	public function afterCreate()
	{
		if ($this->id > 0) {

			$activity = new Activities();
			$activity->users_id = $this->users_id;
			$activity->posts_id = $this->posts_id;
			$activity->type = 'C';
			$activity->save();

			$toNotify = array();

			/**
			 * Notify users that always want notifications
			 */
			foreach (Users::find(array('notifications = "Y"', 'columns' => 'id')) as $user) {
				if ($this->users_id != $user->id) {
					$notification = new Notifications();
					$notification->users_id = $user->id;
					$notification->posts_id = $this->posts_id;
					$notification->posts_replies_id = $this->id;
					$notification->type = 'C';
					$notification->save();
					$toNotify[$user->id] = $notification->id;
				}
			}

			/**
			 * Register the user in the post's notifications
			 */
			if (!isset($toNotify[$this->users_id])) {

				$hasNotifications = PostsNotifications::count(array(
					'users_id = ?0 AND posts_id = ?1',
					'bind' => array($this->users_id, $this->posts_id)
				));

				if (!$hasNotifications) {
					$notification = new PostsNotifications();
					$notification->users_id = $this->users_id;
					$notification->posts_id = $this->posts_id;
					$notification->save();
				}
			}

			/**
			 * Notify users that have commented in the same post
			 */
			$postsNotifications = PostsNotifications::findByPostsId($this->posts_id);
			foreach ($postsNotifications as $postNotification) {
				if (!isset($toNotify[$postNotification->users_id])) {
					if ($postNotification->users_id != $this->users_id) {
						$notification = new Notifications();
						$notification->users_id = $postNotification->users_id;
						$notification->posts_id = $this->posts_id;
						$notification->posts_replies_id = $this->id;
						$notification->type = 'C';
						$notification->save();
						$toNotify[$postNotification->users_id] = $notification->id;
					}
				}
			}
		}
	}

	public function afterSave()
	{
		$this->clearCache();

		$history = new PostsRepliesHistory();
		$history->posts_replies_id = $this->id;
		$usersId = $this->getDI()->getSession()->get('identity');
		if ($usersId) {
			$history->users_id = $usersId;
		} else {
			$history->users_id = $this->users_id;
		}
		$history->content  = $this->content;
		$history->save();
	}

	public function afterDelete()
	{
		$this->clearCache();
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

	public function getHumanEditedAt()
	{
		$diff = time() - $this->edited_at;
		if ($diff > (86400 * 30)) {
			return date('M \'y', $this->edited_at);
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

	public function clearCache()
	{
		if ($this->id) {
			$viewCache = $this->getDI()->getViewCache();
			$viewCache->delete('post-' . $this->posts_id);
			$viewCache->delete('post-body-' . $this->posts_id);
			$viewCache->delete('post-users-' . $this->posts_id);
			$viewCache->delete('reply-body-' . $this->id);
		}
	}

}