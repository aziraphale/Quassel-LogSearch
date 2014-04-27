<?php

namespace QuasselLogSearch;

use Klein\Klein;
use QuasselLogSearch\Controller\Layout;
use QuasselLogSearch\Utility\Authentication;

class Router
{
    /**
     * The requested resource has been assigned a new permanent URI and any future references to this resource SHOULD
     * use one of the returned URIs. Clients with link editing capabilities ought to automatically re-link references to
     * the Request-URI to one or more of the new references returned by the server, where possible. This response is
     * cacheable unless indicated otherwise.
     *
     * If the 301 status code is received in response to a request other than GET or HEAD, the user agent MUST NOT
     * automatically redirect the request unless it can be confirmed by the user, since this might change the conditions
     * under which the request was issued.
     *
     * Note: When automatically redirecting a POST request after receiving a 301 status code, some existing HTTP/1.0
     * user agents will erroneously change it into a GET request.
     *
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.3.2
     */
    const REDIRECT_MOVED_PERMANENTLY = 301;

    /**
     * The requested resource resides temporarily under a different URI. Since the redirection might be altered on
     * occasion, the client SHOULD continue to use the Request-URI for future requests. This response is only cacheable
     * if indicated by a Cache-Control or Expires header field.
     *
     * Note: RFC 1945 and RFC 2068 specify that the client is not allowed to change the method on the redirected
     * request. However, most existing user agent implementations treat 302 as if it were a 303 response, performing a
     * GET on the Location field-value regardless of the original request method. The status codes 303 and 307 have been
     * added for servers that wish to make unambiguously clear which kind of reaction is expected of the client.
     *
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.3.3
     */
    const REDIRECT_FOUND = 302;

    /**
     * The response to the request can be found under a different URI and SHOULD be retrieved using a GET method on that
     * resource. This method exists primarily to allow the output of a POST-activated script to redirect the user agent
     * to a selected resource. The new URI is not a substitute reference for the originally requested resource. The 303
     * response MUST NOT be cached, but the response to the second (redirected) request might be cacheable.
     *
     * Note: Many pre-HTTP/1.1 user agents do not understand the 303 status. When interoperability with such clients is
     * a concern, the 302 status code may be used instead, since most user agents react to a 302 response as described
     * here for 303.
     *
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.3.4
     */
    const REDIRECT_SEE_OTHER = 303;

    /**
     * The requested resource resides temporarily under a different URI. Since the redirection MAY be altered on
     * occasion, the client SHOULD continue to use the Request-URI for future requests. This response is only cacheable
     * if indicated by a Cache-Control or Expires header field.
     *
     * If the 307 status code is received in response to a request other than GET or HEAD, the user agent MUST NOT
     * automatically redirect the request unless it can be confirmed by the user, since this might change the conditions
     * under which the request was issued.
     *
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.3.8
     */
    const REDIRECT_TEMPORARY_REDIRECT = 307;

    /**
     * @var \Klein\Klein
     */
    private static $klein;

    private static $baseUrl;
    private static $baseDir;

    public static function _init()
    {
        self::handleNonRootDirectoryInstallations();

        self::$klein = new Klein();
        self::_initLayout();
        self::_initRoutes();
    }

    /**
     * @link https://github.com/chriso/klein.php/wiki/Sub-Directory-Installation
     */
    private static function handleNonRootDirectoryInstallations()
    {
        $base = dirname($_SERVER['PHP_SELF']);

        // Update request when we have a subdirectory
        if (strlen(ltrim($base, '/'))) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($base));
        }
    }

    public static function baseUrl()
    {
        if (!isset(self::$baseUrl)) {
            self::$baseUrl = sprintf(
                'http%s://%s%s',
                (empty($_SERVER['HTTPS']) ? '' : 's'),
                (empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST']),
                self::baseDir()
            );
        }
        return self::$baseUrl;
    }

    public static function baseDir()
    {
        if (!isset(self::$baseDir)) {
            self::$baseDir = dirname($_SERVER['PHP_SELF']);

            if (DIRECTORY_SEPARATOR !== '/') {
                self::$baseDir = str_replace(DIRECTORY_SEPARATOR, '/', self::$baseDir);
            }

            if (strlen(self::$baseDir) > 1) {
                // i.e. the basedir isn't just "/"
                self::$baseDir .= '/';
            }
        }
        return self::$baseDir;
    }

    public static function redirect($path, $code = self::REDIRECT_FOUND)
    {
        $url = self::baseUrl() . ltrim($path, '/');
        self::$klein->response()->redirect($url, $code);
    }

    private static function _initRoutes()
    {
        // Alias self::$klein to $klein for our callbacks
        $klein = self::$klein;

        $klein->respond(        '/',            "QuasselLogSearch\\Controller\\Core::index");
        $klein->respond('POST', '/login',       "QuasselLogSearch\\Controller\\Login::attemptLogin");
        $klein->respond(        '/logout',      "QuasselLogSearch\\Controller\\Login::logout");

        $klein->respond('GET',  '/search',      "QuasselLogSearch\\Controller\\Search::perform");
    }

    private static function _initLayout()
    {
        // Alias self::$klein to $klein for our callbacks
        $klein = self::$klein;

        $klein->with('!@^/ajax/', function () use ($klein) {
            // All NON-Ajax requests...

            // Handle exceptions => flash the message and redirect to the referrer
            $klein->onError(function ($klein, $err_msg) {
                $klein->service()->flash($err_msg, "error");
                $klein->service()->back();
        });

            // Give it a layout (common header/footer/etc.)
            $klein->service()->layout('src/View/Layout/Layout.php');

            Layout::globalVariables($klein);
        });
    }

    public static function dispatch()
    {
        self::$klein->dispatch();
    }
}
