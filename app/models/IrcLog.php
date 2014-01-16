<?php

namespace Phosphorum\Models;

use Phalcon\Mvc\Model;

/**
 * Class IrcLog
 *
 * @package Phosphorum\Models
 */
class IrcLog extends Model
{

    public $id;

    public $who;

    public $content;

    public $datelog;

    public function initialize()
    {
        $this->setSource('irclog');
    }
}
