<?php

namespace QuasselLogSearch\Utility;

use QuasselLogSearch\Quassel\User;
use QuasselLogSearch\Router;

class Authentication
{
    const SESSION_KEY_NAME = 'quassel_log_search_login';

    private static $loggedIn;

    private static function _init()
    {
        if (!session_id()) {
            session_set_cookie_params(0, Router::baseDir(), null, null, true);
            session_start();
        }
    }

    public static function loggedIn()
    {
        self::_init();

        if (!isset(self::$loggedIn)) {
            // Cache the result of this function as verifying the login may require a DB lookup
            if (!empty($_SESSION[self::SESSION_KEY_NAME])) {
                $loggedInData = self::decodeLoginValue($_SESSION[self::SESSION_KEY_NAME]);
                if ($loggedInData) {
                    self::$loggedIn = $loggedInData;
                } else {
                    self::$loggedIn = false;
                    self::logout();
                }
            } else {
                self::$loggedIn = false;
            }
        }
        return self::$loggedIn;
    }

    private static function encodeLoginValue(User $user)
    {
        return serialize($user);
    }

    private static function decodeLoginValue($value)
    {
        $data = unserialize($value);
        if ($data instanceof User) {
            return $data;
        }
        return null;
    }

    public static function attemptLogin($username, $password)
    {
        self::_init();

        $user = User::loadByUsernameAndPasswordHash($username, self::hashPassword($password));
        if ($user instanceof User) {
            $_SESSION[self::SESSION_KEY_NAME] = self::encodeLoginValue($user);
            return $user;
        }
        return null;
    }

    public static function logout()
    {
        self::_init();
        $_SESSION[self::SESSION_KEY_NAME] = '';
    }

    public static function hashPassword($password)
    {
        // Yep - Quassel password hashes are just plain ol' SHA1 :(
        return sha1($password);
    }
}
