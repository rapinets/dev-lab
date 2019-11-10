<?php
namespace App\Database;

use App\Configuration\Configuration;

class Database
{
    private $config;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        return $this->config->get('db.host');
    }
}
