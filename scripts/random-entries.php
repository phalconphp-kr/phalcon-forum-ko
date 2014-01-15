<?php

/**
 * This scripts generates random posts
 */

require 'cli-bootstrap.php';

$faker = Faker\Factory::create();

for ($i = 0; $i <= 20; $i++) {

    $title = $faker->company;

    $post       = new Phosphorum\Models\Categories();
    $post->name = $title;
    $post->slug = Phalcon\Tag::friendlyTitle($title);

    if (!$post->save()) {

        var_dump($post->getMessages());
        break;
    }

}


$categoryIds = Phosphorum\Models\Categories::find(['columns' => 'id'])->toArray();


for ($i = 0; $i <= 500; $i++) {

    $title   = $faker->company;
    $content = $faker->text();

    $post           = new Phosphorum\Models\Posts();
    $post->title    = $title;
    $post->slug     = Phalcon\Tag::friendlyTitle($title);
    $post->content  = $content;
    $post->users_id = 1;

    $id                  = array_rand($categoryIds);
    $post->categories_id = $categoryIds[$id]['id'];

    if (!$post->save()) {

        var_dump($post->getMessages());
        break;
    }

    $post->category->number_posts++;

    if (!$post->category->save()) {

        var_dump($post->category->getMessages());
        break;
    }
}

