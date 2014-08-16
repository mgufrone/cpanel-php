<?php
use Gufy\CpanelPhp\Cpanel;
class CpanelTest extends PHPUnit_Framework_TestCase
{
  public function testConfiguration()
  {
    $cpanel = new Cpanel($options=array(
      'host'=>'https://103.27.206.138',
      'username'=>'root',
      'password'=>'longhash',
      'auth_type'=>'hash'
    ));

    $this->assertEquals($options['host'], $cpanel->getHost());
    $this->assertEquals($options['username'], $cpanel->getUsername());
    $this->assertEquals($options['password'], $cpanel->getPassword());
    $this->assertEquals($options['auth_type'], $cpanel->getAuthType());
  }
  
}
