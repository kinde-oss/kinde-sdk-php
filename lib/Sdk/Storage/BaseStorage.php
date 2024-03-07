<?php

namespace Kinde\KindeSDK\Sdk\Storage;

use Kinde\KindeSDK\Sdk\Enums\StorageEnums;

class BaseStorage
{
    static $prefix = 'kinde';
    static $storage;
    private static $cookieHttpOnly = false;
    private static $cookiePath = "/";
    private static $cookieDomain = "";

    static function getStorage()
    {
        if (empty(self::$storage)) {
            self::$storage = $_COOKIE['kinde'];
        }
        return self::$storage;
    }

    public static function getItem(string $key)
    {
        return $_COOKIE[self::getKey($key)] ?? "";
    }

    public static function setItem(
        string $key,
        string $value,
        int $expires_or_options = 0,
        string $path = null,
        string $domain = null,
        bool $secure = true,
        bool $httpOnly = null
    ) {
        $newKey = self::getKey($key);
        $_COOKIE[$newKey] = $value;
        setcookie($newKey, $value, $expires_or_options, $path ?? self::$cookiePath, $domain ?? self::$cookieDomain, $secure, $httpOnly ?? self::$cookieHttpOnly);
    }

    public static function removeItem(string $key)
    {
        $newKey = self::getKey($key);
        if (isset($_COOKIE[$newKey])) {
            unset($_COOKIE[$newKey]);
            self::setItem($key, "", -1);
        }
        self::setItem($key, "", -1);
    }

    public static function clear()
    {
        self::removeItem(StorageEnums::TOKEN);
        self::removeItem(StorageEnums::STATE);
        self::removeItem(StorageEnums::CODE_VERIFIER);
        self::removeItem(StorageEnums::USER_PROFILE);
    }

    private static function getKey($key)
    {
        return self::$prefix . '_' . $key;
    }

    public static function setCookieHttpOnly($httpOnly)
    {
        self::$cookieHttpOnly = $httpOnly;
    }

    public static function setCookiePath($cookiePath)
    {
        self::$cookiePath = $cookiePath;
    }

    public static function setCookieDomain($cookieDomain)
    {
        self::$cookieDomain = $cookieDomain;
    }
}
