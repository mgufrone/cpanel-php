<?php
namespace Gufy\CpanelPhp;


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
interface CpanelInterface
{
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
    public function setAuthorization($username, $password);

    /**
     * set API Host.
     *
     * @param string $host Host of your whm server.
     *
     * @return object return as self-object
     *
     * @since v1.0.0
     */
    public function setHost($host);

    /**
     * set Authentication Type.
     *
     * @param string $auth_type Authentication type for calling API.
     *
     * @return object return as self-object
     *
     * @since v1.0.0
     */
    public function setAuthType($auth_type);

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
    public function setHeader($name, $value = '');

    /**
     * get username.
     *
     * @return string return username
     *
     * @since v1.0.0
     */
    public function getUsername();

    /**
     * get authentication type.
     *
     * @return string get authentication type
     *
     * @since v1.0.0
     */
    public function getAuthType();

    /**
     * get password or long hash.
     *
     * @return string get password or long hash
     *
     * @since v1.0.0
     */
    public function getPassword();

    /**
     * get host of your whm server.
     *
     * @return string host of your whm server
     *
     * @since v1.0.0
     */
    public function getHost();

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
    public function cpanel($module, $function, $username, $params = array());
}