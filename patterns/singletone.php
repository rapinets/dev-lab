<?php /** @noinspection ALL */

abstract class Singleton {

    private static $instance = null;

    public static function getInstance()
    {
        if ($a= self::$instance === null) {
            self::$instance = new static(...func_get_args());
        }

        return self::$instance;
    }

    protected function __construct(){}

    protected function __clone(){}
}


class Config extends Singleton {

    private $config = [];

    protected function __construct($a, $b)
    {
        var_dump($a, $b);
        $this->config = [
            'db' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'user' => 'root',
                'password' => ''
            ],
            'app' => [
                'app_name' => 'Super Social site',
                'author' => 'Rapinets',
            ]
        ];
    }

    public function get($prop)
    {
        $array = explode( '.', $prop);

        return $this->config[$array[0]][$array[1]];
    }

    public function set($prop, $value)
    {
        $array = explode( '.', $prop);
        $this->config[$array[0]][$array[1]] = $value;
    }
}

/*
 *  Somewhere in client's code
 */
$config = Config::getInstance(5, 10);
//echo $config->get('app.app_name');
