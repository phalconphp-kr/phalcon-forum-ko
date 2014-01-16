<?php

namespace Phosphorum\Models;

use Phosphorum\Models\Activities;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\Timestampable;

/**
 * Class Users
 *
 * @package Phosphorum\Models
 */
class Users extends Model
{

    public $id;

    public $name;

    public $login;

    public $email;

    public $token_type;

    public $access_token;

    public $gravatar_id;

    public $created_at;

    public $modified_at;

    public $notifications;

    public $timezone;

    public function initialize()
    {
        $params = array(
            'beforeCreate' => array(
                'field' => 'created_at'
            ),
            'beforeUpdate' => array(
                'field' => 'modified_at'
            )
        );
        $this->addBehavior(new Timestampable($params));
    }

    /**
     *
     */
    public function beforeCreate()
    {
        $this->notifications = 'P';
        $this->timezone      = 'Europe/London';
    }

    /**
     *
     */
    public function afterCreate()
    {
        if ($this->id > 0) {
            $activity           = new Activities();
            $activity->users_id = $this->id;
            $activity->type     = 'U';
            $activity->save();
        }
    }
}
