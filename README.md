## cPanel/WHM API for PHP library

## Contents
- [Installation Guide](#installation-guide)
- [Usage](#usage)
- [Functions](#functions)
- [Feedback & Contribution](#feedback-&-contribution)

### Installation Guide

To install this package, you can run this code via your terminal
```shell
	composer require gufy/cpanel-whm:dev-master
```
Or update your `composer.json` by adding this line
```json
	"gufy/cpanel-php":"~1.0"
```

Then, run this code
```shell
	composer update
```

### Usage

For example, if you would like to get list accounts of your whm server, you can do this.

```php
  <?php
  $cpanel = new \Gufy\CpanelPhp\Cpanel([
      'host'        =>  'https://123.456.789.123:2087', // ip or domain complete with its protocol and port
      'username'    =>  'root', // username of your server, it usually root.
      'auth_type'   =>  'hash', // set 'hash' or 'password'
      'password'    =>  'password', // long hash or your user's password
  ]);

  $accounts = $cpanel->listaccts(); // it will returned as array

```

### Functions

- [Defining Configuration on constructor](#defining-configuration-on-constructor)
- [Usage](#usage)
- [Overriding Current configuration](#overriding-current-configuration)
- [Get defined configuration](#get-defined-configuration)

#### Defining Configuration on constructor
This is the example when you want to define your configuration while creating new object

```php
  <?php
  $cpanel = new \Gufy\CpanelPhp\Cpanel([
      'host'        =>  'https://123.456.789.123:2087', // required
      'username'    =>  'root', // required
      'auth_type'   =>  'hash', // optional, default 'hash'
      'password'    =>  'password', // required
  ]);
```

#### Usage
For example, you would like to get some list accounts from cPanel/WHM
```php
	<?php

	$accounts = $cpanel->listaccts();

	// passing parameters
	$accounts = $cpanel->listaccts(['searchtype'=>'domain', 'search'=>'', 'exact', 'search'=>'helloworld.com']);
```

For accessing cPanel API 2, you can use this.

```php
	<?php
	// get bandwidth data of specific cPanel's user
	$data = $cpanel->cpanel('Bandwidth', 'getbwdata', 'username');

	// removing cron line
	$data = $cpanel->cpanel('Cron', 'remove_line', 'username', ['line'=>1]);
```

The first parameter must be Module you would like to get, second is function name, and the third is username of cPanel's user. There is fourth arguments, when function has some additional arguments, you can pass it there.

For accessing to cPanel API 1 or cPanel API 2 or UAPI, you can use this.

```php
	<?php
	// get bandwidth data of specific cPanel's user (using cPanel API 2)
	$data = $cpanel->execute_action('2', 'Bandwidth', 'getbwdata', 'username');

	// removing email address (using UAPI)
	$data = $cpanel->execute_action('3', 'Email', 'delete_pop', 'username', ['email'=>'peter@griffin.com']);
```

This function is similar to the above, the only difference is that it has added a parameter which indicates the API you want to use (1 = cPanel API 1, 2 = cPanel API 2, 3 = UAPI), the other arguments are the same.

#### Overriding current configuration
Somehow, you want to override your current configuration. To do this, here is the code

```php
  <?php
  // change username andd (password or hash)
  $cpanel->setAuthorization($username, $password);

  // change host
  $cpanel->setHost($host);

  // change authentication type
  $cpanel->setAuthType($auth_type);
```

#### Get defined configuration
After you define some of your configuration, you can get it again by calling this functions

```php
  <?php
  // get username
  $cpanel->getUsername();

  // get password
  $cpanel->getPassword();

  // get authentication type
  $cpanel->getAuthType();

  // get host
  $cpanel->getHost();
```

#### Feedback and contribution

This package is free and open source, feel free to fork and report some issue to this package. :-). Have fun
