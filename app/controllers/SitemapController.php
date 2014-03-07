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

use Phosphorum\Models\Posts,
	Phosphorum\Models\PostsReplies,
	Phalcon\Http\Response;

class SitemapController extends \Phalcon\Mvc\Controller
{

	public function initialize()
	{
		$this->view->disable();
	}

	/**
	 * Generate the website sitemap
	 *
	 */
	public function indexAction()
	{

		$response = new Response();

		$expireDate = new \DateTime();
		$expireDate->modify('+1 day');

		$response->setExpires($expireDate);

		$response->setHeader('Content-Type', "application/xml; charset=UTF-8");

		$sitemap = new \DOMDocument("1.0", "UTF-8");

		$urlset = $sitemap->createElement('urlset');
		$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

		$url = $sitemap->createElement('url');
		$url->appendChild($sitemap->createElement('loc', 'http://forum.phalconphp.kr/'));
		$url->appendChild($sitemap->createElement('changefreq', 'daily'));
		$url->appendChild($sitemap->createElement('priority', '1.0'));
		$urlset->appendChild($url);

		$posts = Posts::find(array(
			'columns' => '
				id,
				slug,
				modified_at,
				number_views + ((IF(votes_up IS NOT NULL, votes_up, 0) - IF(votes_down IS NOT NULL, votes_down, 0)) * 4) + number_replies as karma',
			'order' => 'karma DESC'
		));

		$karma = Posts::maximum(array(
			'column' => 'number_views + ((IF(votes_up IS NOT NULL, votes_up, 0) - IF(votes_down IS NOT NULL, votes_down, 0)) * 4) + number_replies'
		));

		foreach ($posts as $post) {

			$modifiedAt = new \DateTime();
			$modifiedAt->setTimezone(new \DateTimeZone('UTC'));
			$modifiedAt->setTimestamp($post->modified_at);

			$postKarma = $post->karma / ($karma + 100);

			$url = $sitemap->createElement('url');
			$url->appendChild($sitemap->createElement('loc', 'http://forum.phalconphp.kr/discussion/' . $post->id . '/' . $post->slug));
			$url->appendChild($sitemap->createElement('priority', $postKarma > 0.7 ? sprintf("%0.1f", $postKarma) : sprintf("%0.1f", $postKarma + 0.25)));
			$url->appendChild($sitemap->createElement('lastmod', $modifiedAt->format('Y-m-d\TH:i:s\Z')));
			$urlset->appendChild($url);
		}

		$sitemap->appendChild($urlset);

		$response->setContent($sitemap->saveXML());
		return $response;
	}

}