<?php

class Dog
{
    public function __invoke(string $who): string
    {
        return 'Woof ' . $who;
    }
}

$dog = new Dog();

echo $dog('James');
echo "\n\n";
echo $dog->__invoke('Tom');
echo "\n\n";
echo call_user_func_array($dog, ['Lena']);
echo "\n\n";