<?php namespace Gufy\CpanelPhp;

class Cpanel
{
  private $username;
  private $password;
  private $auth_type;
  private $host;
  protected $headers=array();
  public function __construct($options=array())
  {
    if(!empty($options['auth_type']))
      $this->setAuthType($options['auth_type']);
    return $this->checkOptions($options)
    ->setHost($options['host'])
    ->setAuthorization($options['username'], $options['password']);
  }
  public function __call($function, $arguments=[])
  {
    return $this->runQuery($function, $arguments);
  }
  public function checkOptions($options)
  {
    if(empty($options['username']))
      throw new \Exception('Username is not set', 2301);
    if(empty($options['password']))
      throw new \Exception('Password or hash is not set', 2302);
    if(empty($options['host']))
      throw new \Exception('CPanel Host is not set', 2303);
    return $this;
  }

  public function setAuthorization($username, $password)
  {
    $this->username = $username;
    $this->password = $password;
    return $this;
  }
  public function setHost($host)
  {
    $this->host = $host;
    return $this;
  }
  public function setAuthType($auth_type)
  {
    $this->auth_type = $auth_type;
    return $this;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function getAuthType()
  {
    return $this->auth_type;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getHost()
  {
    return $this->host;
  }
  public function setHeader($name, $value='')
  {
    $this->headers[$name] = $value;
    return $this;
  }
  public function createHeader()
  {
    $headers = $this->headers;

    $username = $this->getUsername();
    $auth_type = $this->getAuthType();

    if('hash' == $auth_type)
      $headers['Authorization'] = 'WHM '.$username.':'. preg_replace("'(\r|\n)'","",$this->getPassword());
    elseif('password' == $auth_type)
      $headers['Authorization'] = 'Basic '.$username.':'. preg_replace("'(\r|\n)'","",$this->getPassword());

    return $headers;
  }
	public function runQuery($action, $arguments)
	{
    $host = $this->getHost();
		$response = \GuzzleHttp\post($host.'/json-api/'.$action, [
		    'headers' => $this->createHeader(),
		    // 'body'    => $arguments[0],
		    'verify'  => false,
		    'query'	  => $arguments

		]);
		return $response->json();
	}
}
