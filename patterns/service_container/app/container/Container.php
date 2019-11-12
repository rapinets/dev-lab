<?php

namespace App\Container;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class Container
{
    private $items = []; //створюємо масив в якому будемо зберігати ключ($name)=> значення($closure)

    // метод set присваює об'єкту ключ($name) і визиває функцію, яка вертає об'єкт
    public function set($name, callable $closure)
    {
        $this->items[$name] = $closure;
    }

    // метод share теж саме як і метод set лише виконуєся один раз, як singleton
    public function share($name, callable $closure)
    {

        $this->items[$name] = function () use ($closure) {
            //створюємо статичний параметер в який передаємо визов функції замикання($closure)
            static $resolved;
            //перевіряємо чи не існує параметер
            if (!$resolved) {
                //якщо ні то записуємо в нього замикання ($closure) і передаємо їй об'єкт $this->Container
                //робимо ссилку на об'єкт, щоб створювася лише раз при визові
                $resolved = $closure($this);
            }
            //вертаємо статичний об'єкт
            return $resolved;
        };
    }

    //метод get приймає ключ($name) і по ньому створює об'єкт
    public function get($name)
    {
        //якщо існує такий ключ($name)
        if(isset($this->items[$name]))
        {
            //вертаємо об'єкт і виконуємо залежність, яка створює інший об'єкт
            return $this->items[$name]($this);
        }

        //якщо не існує, то виконуємо метод autowire, який створює об'єкт по простору імен
        return $this->autowire($name);
    }

    //autowire функція
    private function autowire($name)
    {
        //перевіряємо якщо нема такого класса то викинемо помилку
        if (!class_exists($name)) {
            throw new Exception('Class ' . $name . ' not exists.');
        }

        //якщо класс то створюємо об'єкт типу ReflectionClass
        $reflector = new ReflectionClass($name);

        //перевіряємо якщо не ма то помилка
        if (!$reflector->isInstantiable()) {
            throw new Exception('Class ' . $name . ' not instantiable.');
        }

        //якщо є вертаємо конструктор класса
        $constructor = $reflector->getConstructor();

        //якщо є конструктор и є параметри
        if($constructor && $constructor->getParameters()){
//            dd($constructor->getParameters());
            //то створюємо екземпляр класса з переданими параметрами ($constructor->getParameters())
            return $reflector->newInstanceArgs(
                $this->getReflectionConstructorDependecies($constructor)
            );
        }

        //якщо немає параметрів створюємо об'єкт
        return new $name($this);
    }

    //метод getReflectionConstructorDependecies приймає конструктор і вертає масив залежностей
    private function getReflectionConstructorDependecies(ReflectionMethod $constructor)
    {
        //визиває функцію callback над усіма елементами масива залежностями($constructor->getParameters())
        return array_map(function (ReflectionParameter $dependency){

            return $this->resolveReflectedDependency($dependency);

        }, $constructor->getParameters());
    }

    //метод який перевіряє чи є в залежностях залежності і вертає get з іменем параметра
    private function resolveReflectedDependency(ReflectionParameter $dependency)
    {

        if (is_null($reflector = $dependency->getClass())) {
            throw new Exception('Class ' . $name . ' not exists.');
        }
        return $this->get($reflector->getName());
    }
}
