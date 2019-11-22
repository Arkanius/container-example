<?php

require_once __DIR__.'/vendor/autoload.php';

class Foo 
{
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }

    public function methodFoo()
    {
        echo 'ok Foo'. PHP_EOL;
        $this->bar->methodBar();
    }
}

class Bar
{
    private $tar;

    public function __construct(Tar $tar)
    {
        $this->tar = $tar;
    }

    public function methodBar()
    {
        echo 'ok Bar'. PHP_EOL;
        $this->tar->methorTar();
    }
}

class Tar 
{
    public function methorTar()
    {
        echo 'ok Tar'. PHP_EOL;
    }
}

class Container
{
    public function resolve($class)
    {
        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $depedencies = $this->getDepedencies($parameters);
        
        return $reflector->newInstanceArgs($depedencies);
    }

    public function getDepedencies($parameters)
    {
        $depedencies = [];

        foreach($parameters as $parameter) {
            if (!is_null($parameter)) {
                $depedencies[] = $this->resolve($parameter->getClass()->name);
            }
        }

        return $depedencies;
    }
}


$container = new Container();
$foo = $container->resolve(Foo::class);
$foo->methodFoo();