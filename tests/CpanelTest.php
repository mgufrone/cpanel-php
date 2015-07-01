<?php
use Gufy\CpanelPhp\Cpanel;
class CpanelTest extends PHPUnit_Framework_TestCase
{
  public function testConfiguration()
  {
    $cpanel = new Cpanel($options=array(
      'host'=>'https://127.0.0.1:2087',
      'username'=>'root',
      'password'=>'password',
      'auth_type'=>'password'
    ));

    $this->assertEquals($options['host'], $cpanel->getHost());
    $this->assertEquals($options['username'], $cpanel->getUsername());
    $this->assertEquals($options['password'], $cpanel->getPassword());
    $this->assertEquals($options['auth_type'], $cpanel->getAuthType());
  }

}
