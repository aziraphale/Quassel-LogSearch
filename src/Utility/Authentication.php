<?php

namespace QuasselLogSearch\Utility;

use QuasselLogSearch\Quassel\User;

class Authentication
{
    const COOKIE_NAME = 'quassel_log_search_login';

    private static $loggedIn;

    public static function loggedIn()
    {
        if (!isset(self::$loggedIn)) {
            // Cache the result of this function as verifying the login may require a DB lookup
            if (!empty($_COOKIE[self::COOKIE_NAME])) {
                $loginCookie = $_COOKIE[self::COOKIE_NAME];
                self::$loggedIn = self::validateLoginCookie($loginCookie);
            } else {
                self::$loggedIn = false;
            }
        }
        return self::$loggedIn;
    }

    private static function generateLoginCookie(User $user)
    {
        /** @todo This really needs to be better... Session plx */
        return sprintf("%s:%s", $user->username, $user->passwordHash);
    }

    private static function validateLoginCookie($cookie)
    {
        if (strpos($cookie, ':') !== false) {
            list ($username, $passwordHash) = explode(':', $cookie, 2);

            $user = User::loadByUsernameAndPasswordHash($username, $passwordHash);
            if ($user instanceof User) {
                return $user;
            }
        }

        // Invalid cookie
        self::logout();
        return false;
    }

    public static function attemptLogin($username, $password)
    {
        $user = User::loadByUsernameAndPasswordHash($username, self::hashPassword($password));
        if ($user instanceof User) {
            // Session cookie; HTTP-only
            setcookie(self::COOKIE_NAME, self::generateLoginCookie($user), null, null, null, null, true);
            return $user;
        }
        return null;
    }

    public static function logout()
    {
        setcookie(self::COOKIE_NAME, false, null, null, null, null, true);
    }

    public static function hashPassword($password)
    {
        // Yep - Quassel password hashes are just plain ol' SHA1 :(
        return sha1($password);
    }
}
