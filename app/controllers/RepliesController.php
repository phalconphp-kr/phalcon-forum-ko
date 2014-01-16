<?php

namespace Phosphorum\Controllers;

use Phosphorum\Models\Posts;
use Phosphorum\Models\PostsReplies;
use    Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

/**
 * Class RepliesController
 *
 * @package Phosphorum\Controllers
 */
class RepliesController extends Controller
{

    /**
     *
     */
    public function initialize()
    {
        $this->view->disable();
    }

    /**
     * Returs the raw comment as it as edited
     *
     * @param $id
     *
     * @return Response
     */
    public function getAction($id)
    {

        $response = new Response();

        $usersId = $this->session->get('identity');
        if (!$usersId) {
            $response->setStatusCode('401', 'Unauthorized');
            return $response;
        }

        $parameters = array(
            'id = ?0 AND users_id = ?1',
            'bind' => array($id, $usersId)
        );
        /** @var PostsReplies $postReply */
        $postReply = PostsReplies::findFirst($parameters);
        if ($postReply) {
            $data = array('status' => 'OK', 'id' => $postReply->id, 'comment' => $postReply->content);
        } else {
            $data = array('status' => 'ERROR');
        }

        $response->setJsonContent($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        return $response;
    }

    /**
     * Updates a reply
     */
    public function updateAction()
    {

        $usersId = $this->session->get('identity');
        if (!$usersId) {
            return $this->response->redirect();
        }

        if (!$this->request->isPost()) {
            return $this->response->redirect();
        }

        $parameters = array(
            'id = ?0 AND users_id = ?1',
            'bind' => array($this->request->getPost('id'), $usersId)
        );
        /** @var PostsReplies $postReply */
        $postReply = PostsReplies::findFirst($parameters);
        if (!$postReply) {
            return $this->response->redirect();
        }

        $content = $this->request->getPost('content');
        if (trim($content)) {
            $postReply->content = $content;
            $postReply->save();
        }

        $urlParams   = array(
            'for'  => 'page-discussion',
            'id'   => $postReply->post->id,
            'slug' => $postReply->post->slug
        );
        $href        = $this->url->get($urlParams);
        $redirectUrl = $href . '#C' . $postReply->id;

        return $this->response->redirect($redirectUrl, true);
    }

    /**
     * Deletes a reply
     *
     * @param int $id
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function deleteAction($id)
    {

        $userId = $this->session->get('identity');
        if (!$userId) {
            return $this->response->setStatusCode('401', 'Unauthorized');
        }

        $parameters = array(
            'id = ?0 AND users_id = ?1',
            'bind' => array($id, $userId)
        );
        /** @var PostsReplies $postReply */
        $postReply = PostsReplies::findFirst($parameters);

        if ($postReply) {

            if ($postReply->delete()) {
                if ($userId != $postReply->post->users_id) {
                    $postReply->post->number_replies--;
                    $postReply->post->save();
                }
            }

            $urlParams   = array(
                'for'  => 'page-discussion',
                'id'   => $postReply->post->id,
                'slug' => $postReply->post->slug
            );
            $redirectUrl = $this->url->get($urlParams);

            return $this->response->redirect($redirectUrl, true);
        }

        return $this->response->redirect();
    }
}
