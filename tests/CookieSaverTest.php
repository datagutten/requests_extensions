<?php


use datagutten\RequestsExtensions\CookieSaver;
use PHPUnit\Framework\TestCase;
use WpOrg\Requests;

class CookieSaverTest extends TestCase
{
    public function testSaveCookies()
    {
        $session = new Requests\Session('https://httpbin.org/');
        $session->get('/cookies/set/cookietest/testcookie');
        $file = sys_get_temp_dir() . '/cookies.json';
        @unlink($file);
        $this->assertFileDoesNotExist($file);
        CookieSaver::saveCookies(CookieSaver::getSessionCookies($session), $file);
        $this->assertFileExists($file);
        $cookies = json_decode(file_get_contents($file), true);
        $this->assertIsArray($cookies);
        $this->assertEquals('cookietest', $cookies[0]['name']);
        $this->assertEquals('testcookie', $cookies[0]['value']);
        //unlink($file);
    }

    public function testLoadCookies()
    {
        $jar = CookieSaver::loadCookies(__DIR__ . '/cookies.json');
        $this->assertInstanceOf(Requests\Cookie\Jar::class, $jar);
        /**
         * @var Requests\Cookie $cookie
         */
        $cookie = $jar[0];
        $this->assertInstanceOf(Requests\Cookie::class, $cookie);
        $this->assertEquals('testcookie', $cookie->value);
    }
}
