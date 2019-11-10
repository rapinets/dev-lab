<?php
namespace App\Controllers;

use App\Configuration\Configuration;
use App\Database\Database;

class HomeController
{
    private $config;
    private $database;

    public function __construct(Configuration $config, Database $database)
    {
        $this->config = $config;
        $this->database = $database;
    }

    public function index()
    {
        return [
            $this->config->get('app.name'),
            $this->database->connect()
        ];
    }
}
