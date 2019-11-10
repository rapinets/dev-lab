<?php

class Registry {

    private $registry = [];

    public function set($alias, $value)
    {
        if (!$this->registry[$alias]){
            $this->registry[$alias] = $value;
        }
        return $this->get($alias);
    }


    public function get($alias)
    {
        return $this->registry[$alias];
    }
}

/* Client Code */
class Configuration {

    private $config = [];

    public function __construct()
    {
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

$app = new Registry();

/* Set function */
//$app->set('config', function () { return new Configuration; });

/* Set Object */
$config = $app->set('config', new Configuration);

$config = $app->get('config');

echo '<pre>';
print_r($app->get('config'));
