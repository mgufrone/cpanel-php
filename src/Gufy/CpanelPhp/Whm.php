<?php namespace Gufy\CpanelPhp;

/**
 * WHM API
 *
 * Provides easy to use class for calling WHM API 1 functions.
 *
 * @author Adnan RIHAN <adnan@rihan.fr>
 *
 * @version v1.0.0
 *
 * @link https://github.com/max13/cpanel-php
 * @since v2.0.0
 */
class Whm extends Cpanel
{
    /**
     * Magic method who will call the WHM Api 1.
     *
     * @param string $function function name that will be called
     * @param array $arguments parameter that should be passed when calling API function
     *
     * @return array result of called functions
     *
     * @since v2.0.0
     */
    public function __call($function, $arguments)
    {
        if (count($arguments) === 0) {
            $arguments[0] = [];
        }

        if (!array_key_exists('api.version', $arguments[0])) {
            $arguments[0]['api.version'] = 1;
        }

        array_unshift($arguments, $function);

        return call_user_func_array([$this, 'runQuery'], $arguments);
    }

    /**
     * The executor. It will run API function and get the data.
     *
     * @param string $action function name that will be called.
     * @param string $arguments list of parameters that will be attached.
     * @param bool   $throw defaults to true, if set to false: returns error message
     *
     * @return array results of API call
     *
     * @throws Exception
     *
     * @since v2.0.0
     */
    protected function runQuery($action, $arguments, $throw = true)
    {
        try {
            $response = parent::runQuery($action, $arguments, true);

            if (!$response['metadata']['result'] && $throw) {
                throw new \Exception($response['metadata']['reason']);
            }

            return $response['data'];
        } catch (\Exception $e) {
            if ($throw) {
                throw $e;
            }

            return $e->getMessage();
        }

    }
}
