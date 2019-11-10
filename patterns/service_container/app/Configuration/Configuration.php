<?php

namespace App\Configuration;

class Configuration
{
    private $config = [
        'app' => [
            'name' => 'Container with auto wiring'
        ],
        'db' => [
            'host' => '127.0.0.1',
            'database' => 'container',
            'username' => 'root',
            'password' => '',
        ]
    ];

    private $cache = [];

    public function __construct()
    {
        dump(__METHOD__);
    }

    public function get($key, $default = null)
    {
        $value = $this->fromConfig($key);

        return $this->existsInCache($key) ? $this->fromCache($key)
            : $this->toCache($key, $value ? $value : $default);
    }

    // Get a value from config
    private function fromConfig($key)
    {
        $filtered = $this->config;

        foreach (explode('.', $key) as $segment) {
            if ($this->exists($filtered, $segment)) {
                $filtered = $filtered[$segment];
                continue;
            }
            return false;
        }

        return $filtered;
    }

    // Add config key=>value pair to cache
    private function toCache($key, $value)
    {
        $this->cache[$key] = $value;
        return $value;
    }

    // Get value from cache by key
    private function fromCache($key)
    {
        return $this->cache[$key];
    }

    private function existsInCache($key)
    {
        return isset($this->cache[$key]);
    }

    // If the key exists in a config array
    private function exists(array $config, $key)
    {
        return array_key_exists($key, $config);
    }
}
