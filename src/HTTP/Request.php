<?php

namespace API\HTTP;

use API\Exception\InvalidRoute;

readonly class Request
{
    public function __construct(
        public string $post, public array $get, public array $server, public array $files)
    {
    }

    public static function Init(): static
    {
        return new static(file_get_contents('php://input'), $_GET, $_SERVER, $_FILES);
    }

    public function URI(): string
    {
        return $this->server['REQUEST_URI'];
    }

    public function method(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getClientIP(): string
    {
        $remote = new RemoteAddress();
        $remote->setUseProxy();
        $remote->setTrustedProxies(CLOUDFLARE_IP_LIST);
        $remote->setProxyHeader('HTTP_CF_CONNECTING_IP');
        return $remote->getIpAddress();
    }

    public function getOS(): string
    {
        $os_platform = $_SERVER['HTTP_USER_AGENT'];
        $os_array = [
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        ];

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $_SERVER['HTTP_USER_AGENT']))
                $os_platform = $value;

        return $os_platform;
    }

    public function getBrowser(): string
    {
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $browser_array = [
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        ];

        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $_SERVER['HTTP_USER_AGENT']))
                $browser = $value;

        return $browser;
    }

    /**
     * @throws InvalidRoute
     */
    public function getPOSTObject(): object
    {
        return json_decode($this->post) ?? throw new InvalidRoute(5);
    }

    /**
     * @throws InvalidRoute
     */
    public function getPOSTArray(): array
    {
        return json_decode($this->post, true) ?? throw new InvalidRoute(5);
    }

    public function getGET(): array
    {
        return $this->get;
    }
}