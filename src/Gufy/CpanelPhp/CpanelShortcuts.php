<?php namespace Gufy\CpanelPhp;

/**
 * Trait CpanelShortcuts
 *
 * A handful of shortcuts for getting things done(tm)
 *
 * @package Gufy\CpanelWhm
 */
trait CpanelShortcuts
{
    /**
     * List all the accounts that the reseller has access to.
     *
     * @return mixed
     */
    public function listAccounts()
    {
        return $this->runQuery('listaccts', []);
    }

    /**
     * Create a new account
     *
     * @param $domain_name
     * @param $username
     * @param $password
     * @param $plan
     *
     * @return mixed
     */
    public function createAccount($domain_name, $username, $password, $plan)
    {
        return $this->runQuery('createacct', [
            'username' => $username,
            'domain' => $domain_name,
            'password' => $password,
            'plan' => $plan,
        ]);
    }

    /**
     * This function deletes a cPanel or WHM account.
     *
     * @param string $username
     */
    public function destroyAccount($username)
    {
        return $this->runQuery('removeacct', [
            'username' => $username,
        ]);
    }

    /**
     * Gets the email addresses that exist under a cPanel account
     *
     * @param $username
     */
    public function listEmailAccounts($username)
    {
        return $this->cpanel('Email', 'listpops', $username);
    }

    /**
     * @param $username **cPanel username**
     * @param $email email address to add
     * @param $password password **for the email address**
     * @return mixed
     * @throws \Exception
     */
    public function addEmailAccount($username, $email, $password)
    {
        list($account, $domain) = $this->split_email($email);

        return $this->emailAction('addpop', $username, $password, $domain, $account);
    }

    /**
     * Change the password for an email account in cPanel
     *
     * @param $username
     * @param $email
     * @param $password
     * @return mixed
     * @throws \Exception
     */
    public function changeEmailPassword($username, $email, $password)
    {
        list($account, $domain) = $this->split_email($email);

        return $this->emailAction('passwdpop', $username, $password, $domain, $account);
    }

    /**
     * Runs a blank API Request to pull cPanel's response.
     *
     * @return array [status (0 is fail, 1 is success), error (internal error code), verbose (Extended error message)]
     */
    public function checkConnection()
    {
        try {
            $this->runQuery('', []);
        } catch (\Exception $e) {
            if ($e->hasResponse()) {
                switch ($e->getResponse()->getStatusCode()) {
                    case 403:
                        return [
                            'status' => 0,
                            'error' => 'auth_error',
                            'verbose' => 'Check Username and Password/Access Key.'
                        ];
                    default:
                        return [
                            'status' => 0,
                            'error' => 'unknown',
                            'verbose' => 'An unknown error has occurred. Server replied with: ' . $e->getResponse()->getStatusCode()
                        ];
                }
            } else {
                return [
                    'status' => 0,
                    'error' => 'conn_error',
                    'verbose' => 'Check CSF or hostname/port.'
                ];
            }
            return false;
        }

        return [
            'status' => 1,
            'error' => false,
            'verbose' => 'Everything is working.'
        ];
    }

    /**
     * Split an email address into two items, username and host.
     *
     * @param $email
     * @return array
     * @throws \Exception
     */
    private function split_email($email)
    {
        $email_parts = explode('@', $email);
        if (count($email_parts) !== 2) {
            throw new \Exception("Email account is not valid.");
        }

        return $email_parts;
    }

    /**
     * Perform an email action
     *
     * @param $action
     * @param $username
     * @param $password
     * @param $domain
     * @param $account
     * @return mixed
     */
    private function emailAction($action, $username, $password, $domain, $account)
    {
        return $this->cpanel('Email', $action, $username, [
            'domain' => $domain,
            'email' => $account,
            'password' => $password,
        ]);
    }
}
