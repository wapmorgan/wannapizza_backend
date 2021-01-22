<?php
namespace App\Models;

class Product
{
    public $pizzeria;
    public $type;
    public $name;
    public $price;
    public $pizzaArea;
    public $pizzaCmPrice;
    public $thumbnail;
    public $consumers;

    public function __construct(array $params)
    {
        foreach ($params as $param => $value)
            $this->{$param} = $value;

        $consumers = $this->pizzaArea / Selector::NEEDED_ONE_IN_CM;
        $after_point = $consumers * 10 % 10;
        if ($after_point <= 2)
            $this->consumers = floor($consumers);
        else if ($after_point >= 8)
            $this->consumers = ceil($consumers);
        else
            $this->consumers = floor($consumers).'-'.ceil($consumers);
    }
}
