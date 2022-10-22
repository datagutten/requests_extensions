<?php

namespace datagutten\RequestsExtensions;

use WpOrg\Requests;

class CookieSaver
{
    /**
     * Extract a cookie jar from a session
     * @param Requests\Session $session
     * @return Requests\Cookie\Jar
     */
    public static function getSessionCookies(Requests\Session $session): Requests\Cookie\Jar
    {
        return $session->options["cookies"];
    }

    /**
     * Save cookies to a JSON file
     * @param Requests\Cookie\Jar $jar
     * @param string $file
     * @return bool|int
     */
    public static function saveCookies(Requests\Cookie\Jar $jar, string $file): bool|int
    {
        return file_put_contents($file, json_encode(self::getCookies($jar)));
    }

    /**
     * Extract cookies from a cookie jar as array
     * @param Requests\Cookie\Jar $jar
     * @return Requests\Cookie[]
     */
    public static function getCookies(Requests\Cookie\Jar $jar): array
    {
        $cookies = [];
        foreach ($jar as $cookie) {
            $cookies[] = $cookie;
        }
        return $cookies;
    }

    /**
     * Restore a cookie jar from a JSON file
     * @param string $file
     * @return Requests\Cookie\Jar
     */
    public static function loadCookies(string $file): Requests\Cookie\Jar
    {
        $cookies = json_decode(file_get_contents($file), true);
        $cookie_objs = [];
        foreach ($cookies as $cookie) {
            $cookie_objs[] = new Requests\Cookie($cookie['name'], $cookie['value'], $cookie['attributes'], $cookie['flags'], $cookie['reference_time']);
        }
        return new Requests\Cookie\Jar($cookie_objs);
    }
}