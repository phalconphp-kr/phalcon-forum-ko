<?php

namespace Phosphorum\Controllers;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Controller;
use Phosphorum\Github\OAuth;
use Phosphorum\Github\Users as GithubUsers;
use Phosphorum\Models\Users as ForumUsers;
use Phosphorum\Models\Posts;
use Phosphorum\Models\PostsReplies;
use Phosphorum\Models\Activities;

/**
 * Class SessionController
 *
 * @package Phosphorum\Controllers
 */
class UsersController extends Controller
{

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    protected function indexRedirect()
    {
        return $this->response->redirect();
    }

    /**
     * Returns to the discussion
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    protected function discussionsRedirect()
    {

        $referer = $this->request->getHTTPReferer();

        $path = parse_url($referer, PHP_URL_PATH);

        $this->router->handle($path);
        $matched = $this->router->wasMatched();

        return $matched ? $this->response->redirect($path, true) : $this->indexRedirect();
    }

    /**
     * @return \Phalcon\Http\ResponseInterface|void
     */
    public function authorizeAction()
    {

        if (!$this->session->get('identity')) {

            $oauth = new OAuth($this->config->github);
            return $oauth->authorize();
        }

        return $this->discussionsRedirect();
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function accessTokenAction()
    {
        $oauth = new OAuth($this->config->github);

        $response = $oauth->accessToken();

        if (is_array($response)) {

            if (isset($response['error'])) {
                $this->flashSession->error('Github: ' . $response['error']);
                return $this->indexRedirect();
            }

            $githubUser = new GithubUsers($response['access_token']);

            if (!$githubUser->isValid()) {
                $this->flashSession->error('Invalid Github response');
                return $this->indexRedirect();
            }

            /**
             * Edit/Create the user
             */
            $user = ForumUsers::findFirstByAccessToken($response['access_token']);
            if ($user == false) {
                $user               = new ForumUsers();
                $user->token_type   = $response['token_type'];
                $user->access_token = $response['access_token'];
            }

            /**
             * Update the user information
             */
            $user->name        = $githubUser->getName();
            $user->login       = $githubUser->getLogin();
            $user->email       = $githubUser->getEmail();
            $user->gravatar_id = $githubUser->getGravatarId();

            if (!$user->save()) {
                foreach ($user->getMessages() as $message) {
                    $this->flashSession->error((string)$message);
                    return $this->indexRedirect();
                }
            }

            /**
             * Store the user data in session
             */
            $this->session->set('identity', $user->id);
            $this->session->set('identity-name', $user->name);
            $this->session->set('identity-gravatar', $user->gravatar_id);
            $this->session->set('identity-timezone', $user->timezone);

            if ($user->getOperationMade() == Model::OP_CREATE) {
                $this->flashSession->success('Welcome ' . $user->name);
            } else {
                $this->flashSession->success('Welcome back ' . $user->name);
            }

            return $this->discussionsRedirect();
        }

        $this->flashSession->error('Invalid Github response');
        return $this->discussionsRedirect();
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function logoutAction()
    {
        $this->session->remove('identity');

        $this->flashSession->success('Goodbye!');
        return $this->discussionsRedirect();
    }

    public function shadowLoginAction()
    {
        /**
         * Store the user data in session
         */
        $this->session->set('identity', 1);
        $this->session->set('identity-name', 'Phalcon');
        $this->session->set('identity-gravatar', '5d6f567f9109789fd9f702959768e35d');
        $this->session->set('identity-timezone', 'America/Bogota');
    }

    /**
     * Shows the user profile
     *
     * @param $id
     * @param $username
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function profileAction($id, $username)
    {
        if ($id) {
            $user = ForumUsers::findFirstById($id);
        } else {
            $user = ForumUsers::findFirstByLogin($username);
        }

        if (!$user) {
            $this->flashSession->error('The user does not exist');
            return $this->response->redirect();
        }

        $this->view->user = $user;

        $parametersPosts         = array(
            'users_id = ?0',
            'bind' => array($user->id)
        );
        $this->view->numberPosts = Posts::count($parametersPosts);

        $parametersPostsReplies    = array(
            'users_id = ?0',
            'bind' => array($user->id)
        );
        $this->view->numberReplies = PostsReplies::count($parametersPostsReplies);

        $parametersActivities   = array(
            'users_id = ?0',
            'bind'  => array($id),
            'order' => 'created_at DESC',
            'limit' => 15
        );
        $this->view->activities = Activities::find($parametersActivities);

        $this->tag->setTitle('Profile');
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function settingsAction()
    {

        $usersId = $this->session->get('identity');
        if (!$usersId) {
            $this->flashSession->error('You must be logged first');
            return $this->response->redirect();
        }

        $user = ForumUsers::findFirstById($usersId);
        if (!$user) {
            $this->flashSession->error('The user does not exist');
            return $this->response->redirect();
        }

        if ($this->request->isPost()) {
            $user->timezone      = $this->request->getPost('timezone');
            $user->notifications = $this->request->getPost('notifications');
            if ($user->save()) {
                $this->session->get('timezone', $user->timezone);
                $this->flashSession->success('Settings were successfully updated');
                return $this->response->redirect();
            }
        } else {
            $this->tag->displayTo('timezone', $user->timezone);
            $this->tag->displayTo('notifications', $user->notifications);
        }

        $this->tag->setTitle('My Settings');
        $this->tag->setAutoEscape(false);

        $this->view->user      = $user;
        $this->view->timezones = $this->timezones;
    }
}
