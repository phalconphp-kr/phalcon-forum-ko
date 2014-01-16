<?php

namespace Phosphorum\Models;

use Phalcon\Mvc\Model;

/**
 * Class PostsViews
 *
 * @package \Phosphorum\Models
 * @property \Phosphorum\Models\Posts $post
 */
class PostsViews extends Model
{

    public $id;

    public $posts_id;

    public $ipaddress;

    public function initialize()
    {
        $this->belongsTo(
             'posts_id',
                 'Phosphorum\Models\Posts',
                 'id',
                 array(
                     'alias' => 'post'
                 )
        );
    }
}
