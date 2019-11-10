<?php

namespace App\Container;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class Container
{
    private $items = [];

    // set
    public function set($name, callable $closure)
    {
        $this->items[$name] = $closure;
    }

    public function share($name, callable $closure)
    {
        $this->items[$name] = function () use ($closure) {
            static $resolved;
            if (!$resolved) {
                $resolved = $closure($this);
            }
            return $resolved;
        };
    }

    public function get($name)
    {
        if(isset($this->items[$name]))
        {
            return $this->items[$name]($this);
        }

        return $this->autowire($name);
    }

    private function autowire($name)
    {
        if (!class_exists($name)) {
            throw new Exception('Class ' . $name . ' not exists.');
        }

        $reflector = new ReflectionClass($name);

        if (!$reflector->isInstantiable()) {
            throw new Exception('Class ' . $name . ' not instantiable.');
        }

        $constructor = $reflector->getConstructor();

        if($constructor && $constructor->getParameters()){
//            dd($constructor->getParameters());
            return $reflector->newInstanceArgs(
                $this->getReflectionConstructorDependecies($constructor)
            );
        }

        return new $name($this);
    }

    private function getReflectionConstructorDependecies(ReflectionMethod $constructor)
    {
        return array_map(function (ReflectionParameter $dependency){

            return $this->resolveReflectedDependency($dependency);

        }, $constructor->getParameters());
    }

    private function resolveReflectedDependency(ReflectionParameter $dependency)
    {
        if (is_null($reflector = $dependency->getClass())) {
            throw new Exception('Class ' . $name . ' not exists.');
        }
        return $this->get($reflector->getName());
    }
}
