<?php

namespace Phosphorum\Github;

use \Phalcon\DI\Injectable;

/**
 * Class OAuth
 *
 * @package Phosphorum\Github
 */
class OAuth extends Injectable
{

    protected $endPointAuthorize = 'https://github.com/login/oauth/authorize';

    protected $endPointAccessToken = 'https://github.com/login/oauth/access_token';

    protected $redirectUriAuthorize;

    protected $baseUri;

    protected $clientId;

    protected $clientSecret;

    protected $transport;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->redirectUriAuthorize = $config->redirectUri;
        $this->clientId             = $config->clientId;
        $this->clientSecret         = $config->clientSecret;
    }

    /**
     *
     */
    public function authorize()
    {
        $this->view->disable();

        $key   = $this->security->getTokenKey();
        $token = $this->security->getToken();

        $url
            =
            $this->endPointAuthorize . '?client_id=' . $this->clientId . '&redirect_uri=' . $this->redirectUriAuthorize
            . urlencode('&statekey=' . $key)
            . // add the tokenkey as a query param. Then we will be able to use it to check token authenticity
            '&state=' . $token . '&scope=user:email';

        $this->response->redirect($url, true);
    }

    /**
     * @return bool|mixed
     */
    public function accessToken()
    {

        // check the securtity - anti csrf token
        $key   = $this->request->getQuery('statekey');
        $value = $this->request->getQuery('state');

        if (!$this->di->get("security")->checkToken($key, $value)) {
            return false;
        }

        $this->view->disable();
        $parameters = array(
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $this->request->getQuery('code'),
            'state'         => $this->request->getQuery('state')
        );


        $response = $this->send($this->endPointAccessToken, $parameters);

        return $response;
    }

    /**
     * @param     $url
     * @param     $parameters
     * @param int $method
     *
     * @return bool|mixed
     */
    public function send($url, $parameters, $method = \HttpRequest::METH_POST)
    {
        try {

            $transport = $this->getTransport();

            $headers = array(
                'Accept' => 'application/json'
            );
            $transport->setHeaders($headers);

            $transport->setUrl($url);
            $transport->setMethod($method);

            switch ($method) {
                case \HttpRequest::METH_POST:
                    $transport->addPostFields($parameters);
                    break;
                case \HttpRequest::METH_GET:
                    $transport->addQueryData($parameters);
                    break;
            }

            $transport->send();

            return json_decode($transport->getResponseBody(), true);

        } catch (\HttpInvalidParamException $e) {
            return false;
        } catch (\HttpRequestException $e) {
            return false;
        }

    }

    /**
     * @return \HttpRequest
     */
    public function getTransport()
    {
        if (!$this->transport) {
            $this->transport = new \HttpRequest();
        }
        return $this->transport;
    }
}
