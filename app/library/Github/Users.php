<?php

namespace Phosphorum\Github;

/**
 * Class Users
 *
 * @package Phosphorum\Github
 */
class Users
{

    protected $endPoint = 'https://api.github.com';

    protected $accessToken;

    /**
     * @param $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->_response   = $this->request('/user');
    }

    /**
     * @param $method
     *
     * @return mixed|null
     */
    public function request($method)
    {
        try {
            $transport = new \HttpRequest($this->endPoint . $method . '?access_token=' . $this->accessToken);
            $transport->send();
            return json_decode($transport->getResponseBody(), true);
        } catch (\HttpInvalidParamException $e) {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return is_array($this->_response);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        if ($this->_response['name']) {
            return $this->_response['name'];
        }
        return $this->_response['login'];
    }

    /**
     * @return null
     */
    public function getEmail()
    {
        if ($this->_response['email']) {
            return $this->_response['email'];
        }

        $emails = $this->request('/user/emails');
        if (count($emails)) {
            return $emails[0];
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->_response['login'];
    }

    /**
     * @return mixed
     */
    public function getGravatarId()
    {
        return $this->_response['gravatar_id'];
    }
}
