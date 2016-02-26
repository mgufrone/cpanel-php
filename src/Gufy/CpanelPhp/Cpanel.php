<?php namespace Gufy\CpanelPhp;

use GuzzleHttp\Client;

/**
 * cPanel/WHM API
 *
 * Provides easy to use class for calling some CPanel/WHM API functions.
 *
 * @author Mochamad Gufron <mgufronefendi@gmail.com>
 *
 * @version v1.0.2
 *
 * @link https://github.com/mgufrone/cpanel-php
 * @since v1.0.0
 */
class Cpanel implements CpanelInterface
{
    use CpanelShortcuts;

    /**
     * @var string Username of your whm server. Must be string
     *
     * @since v1.0.0
     */
    private $username;

    /**
     * @var string Password or long hash of your whm server.
     *
     * @since v1.0.0
     */
    private $password;

    /**
     * @var string Authentication type you want to use. You can set as 'hash' or 'password'.
     *
     * @since v1.0.0
     */
    private $auth_type;

    /**
     * @var string Host of your whm server. You must set it with full host with its port and protocol.
     *
     * @since v1.0.0
     */
    private $host;

    /**
     * @var string Sets of headers that will be sent at request.
     *
     * @since v1.0.0
     */
    protected $headers = array();

    /**
     * @var integer Query timeout (Guzzle option)
     *
     * @since v1.0.0
     */
    protected $timeout = 10;

    /**
     * @var integer Connection timeout (Guzzle option)
     *
     * @since v1.0.0
     */
    protected $connection_timeout = 2;

    /**
     * Class constructor. The options must be contain username, host, and password.
     *
     * @param array $options options that will be passed and processed
     *
     * @return self
     * @since v1.0.0
     */
    public function __construct($options = array())
    {
        if (!empty($options)) {
            if (!empty($options['auth_type'])) {
                $this->setAuthType($options['auth_type']);
            }

            return $this->checkOptions($options)
                ->setHost($options['host'])
                ->setAuthorization($options['username'], $options['password']);
        }
    }

    /**
     * Magic method who will call the CPanel/WHM Api.
     *
     * @param string $function function name that will be called
     * @param array $arguments parameter that should be passed when calling API function
     *
     * @return array result of called functions
     *
     * @since v1.0.0
     */
    public function __call($function, $arguments)
    {
        if (count($arguments) > 0)
            $arguments = $arguments[0];
        return $this->runQuery($function, $arguments);
    }

    /**
     * checking options for 'username', 'password', and 'host'. If they are not set, some exception will be thrown.
     *
     * @param array $options list of options that will be checked
     *
     * @return self
     * @throws \Exception
     * @since v1.0.0
     */
    private function checkOptions($options)
    {
        if (empty($options['username'])) {
            throw new \Exception('Username is not set', 2301);
        }
        if (empty($options['password'])) {
            throw new \Exception('Password or hash is not set', 2302);
        }
        if (empty($options['host'])) {
            throw new \Exception('CPanel Host is not set', 2303);
        }

        return $this;
    }

    /**
     * set authorization for access.
     * It only set 'username' and 'password'.
     *
     * @param string $username Username of your whm server.
     * @param string $password Password or long hash of your whm server.
     *
     * @return object return as self-object
     *
     * @since v1.0.0
     */
    public function setAuthorization($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * set API Host.
     *
     * @param string $host Host of your whm server.
     *
     * @return object return as self-object
     *
     * @since v1.0.0
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * set Authentication Type.
     *
     * @param string $auth_type Authentication type for calling API.
     *
     * @return object return as self-object
     *
     * @since v1.0.0
     */
    public function setAuthType($auth_type)
    {
        $this->auth_type = $auth_type;

        return $this;
    }

    /**
     * set some header.
     *
     * @param string $name key of header you want to add
     * @param string $value value of header you want to add
     *
     * @return object return as self-object
     *
     * @since v1.0.0
     */
    public function setHeader($name, $value = '')
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * set timeout.
     *
     * @param $timeout
     *
     * @return object return as self-object
     *
     * @since v1.0.0
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int) $timeout;

        return $this;
    }

    /**
     * set connection timeout.
     *
     * @param $connection_timeout
     *
     * @return object return as self-object
     *
     * @since v1.0.0
     */
    public function setConnectionTimeout($connection_timeout)
    {
        $this->connection_timeout = (int) $connection_timeout;

        return $this;
    }

    /**
     * get username.
     *
     * @return string return username
     *
     * @since v1.0.0
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * get authentication type.
     *
     * @return string get authentication type
     *
     * @since v1.0.0
     */
    public function getAuthType()
    {
        return $this->auth_type;
    }

    /**
     * get password or long hash.
     *
     * @return string get password or long hash
     *
     * @since v1.0.0
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * get host of your whm server.
     *
     * @return string host of your whm server
     *
     * @since v1.0.0
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * get timeout.
     *
     * @return integer timeout of the Guzzle request
     *
     * @since v1.0.0
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * get connection timeout.
     *
     * @return integer connection timeout of the Guzzle request
     *
     * @since v1.0.0
     */
    public function getConnectionTimeout()
    {
        return $this->connection_timeout;
    }

    /**
     * Extend HTTP headers that will be sent.
     *
     * @return array list of headers that will be sent
     *
     * @since v1.0.0
     */
    private function createHeader()
    {
        $headers = $this->headers;

        $username = $this->getUsername();
        $auth_type = $this->getAuthType();

        if ('hash' == $auth_type) {
            $headers['Authorization'] = 'WHM ' . $username . ':' . preg_replace("'(\r|\n|\s|\t)'", '', $this->getPassword());
        } elseif ('password' == $auth_type) {
            $headers['Authorization'] = 'Basic ' . base64_encode($username . ':' .$this->getPassword());
        }
        return $headers;
    }

    /**
     * The executor. It will run API function and get the data.
     *
     * @param string $action function name that will be called.
     * @param string $arguments list of parameters that will be attached.
     *
     * @return array results of API call
     *
     * @since v1.0.0
     */
    protected function runQuery($action, $arguments)
    {
        $host = $this->getHost();
        $client = new Client(['base_uri' => $host]);
        try{
          $response = $client->post('/json-api/' . $action, [
              'headers' => $this->createHeader(),
              // 'body'    => $arguments[0],
              'verify' => false,
              'query' => $arguments,
              'timeout' => $this->getTimeout(),
              'connect_timeout' => $this->getConnectionTimeout()
          ]);

          return (string) $response->getBody();
        }
        catch(\GuzzleHttp\Exception\ClientException $e)
        {
          return $e->getMessage();
        }
    }

    /**
     * Use a cPanel API
     *
     * @param $module
     * @param $function
     * @param $username
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function cpanel($module, $function, $username, $params = array())
    {
        $action = 'cpanel';
        $params = array_merge($params, [
            'cpanel_jsonapi_version' => 2,
            'cpanel_jsonapi_module' => $module,
            'cpanel_jsonapi_func' => $function,
            'cpanel_jsonapi_user' => $username,
        ]);

        $response = $this->runQuery($action, $params);
        return $response;
    }

    /**
     * Use cPanel API 1 or use cPanel API 2 or use UAPI.
     *
     * @param $api (1 = cPanel API 1, 2 = cPanel API 2, 3 = UAPI)
     * @param $module
     * @param $function
     * @param $username
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function execute_action($api, $module, $function, $username, $params = array())
    {
        $action = 'cpanel';
        $params = array_merge($params, [
            'cpanel_jsonapi_apiversion' => $api,
            'cpanel_jsonapi_module' => $module,
            'cpanel_jsonapi_func' => $function,
            'cpanel_jsonapi_user' => $username,
        ]);
        $response = $this->runQuery($action, $params);

        return $response;
    }
}
