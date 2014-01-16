<?php

namespace Phosphorum\Controllers;

use Phosphorum\Models\Posts;
use Phalcon\Http\Response;
use \Phalcon\Mvc\Controller;

/**
 * Class SitemapController
 *
 * @package Phosphorum\Controllers
 */
class SitemapController extends Controller
{

    public function initialize()
    {
        $this->view->disable();
    }

    /**
     * Generate the website sitemap
     *
     * @return Response
     */
    public function indexAction()
    {

        $sitemap = new \DOMDocument("1.0", "UTF-8");

        $urlset = $sitemap->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $url = $sitemap->createElement('url');
        $url->appendChild($sitemap->createElement('loc', $this->url->getBaseUri()));
        $url->appendChild($sitemap->createElement('changefreq', 'daily'));
        $url->appendChild($sitemap->createElement('priority', '1.0'));
        $urlset->appendChild($url);

        /** @var Posts[] $posts */
        $posts = Posts::find(array('order' => 'number_replies DESC'));

        foreach ($posts as $post) {
            $url        = $sitemap->createElement('url');
            $hrefParams = array('for' => 'page-discussion', 'id' => $post->id, 'slug' => $post->slug);
            $href       = $this->url->get($hrefParams);
            $url->appendChild($sitemap->createElement('loc', $href));
            $url->appendChild($sitemap->createElement('priority', '0.8'));
            $url->appendChild($sitemap->createElement('lastmod', $post->getUTCModifiedAt()));
            $urlset->appendChild($url);
        }

        $sitemap->appendChild($urlset);

        $response = new Response();

        $expireDate = new \DateTime();
        $expireDate->modify('+1 day');

        $response->setExpires($expireDate);

        $response->setHeader('Content-Type', "application/xml; charset=UTF-8");

        $response->setContent($sitemap->saveXML());

        return $response;
    }
}
