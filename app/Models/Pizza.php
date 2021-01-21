<?php
namespace App\Models;

class Pizza extends Product
{
    public const DOUGH_NORMAL = 1,
        DOUGH_THIN = 2,
        DOUGH_ULTRA = 3;

    public $type = 'pizza';
    public $id;
    public $sizeId;
    public $size;
    public $tastes;
    public $meat;
    public $ingredients;
    public $dough;
}
