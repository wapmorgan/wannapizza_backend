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

    public function __construct(array $params)
    {
        foreach ($params as $param => $value)
            $this->{$param} = $value;
    }
}
