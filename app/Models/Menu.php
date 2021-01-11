<?php
namespace App\Models;

class Menu
{
    public const CHEESE_TASTE = 'cheese';
    public const SAUSAGE_TASTE = 'sausage';
    public const SPICY_TASTE = 'spicy';
    public const VEGETARIAN_TASTE = 'vegetarian';
    public const EXOTIC_TASTE = 'exotic';

    public const CHICKEN_MEAT = 'chicken';
    public const PORK_MEAT = 'pork';
    public const BEEF_MEAT = 'beef';

    public static array $tastes = [
        self::CHEESE_TASTE => 'Сырная',
        self::SAUSAGE_TASTE => 'Колбасная',
        self::SPICY_TASTE => 'Острая',
        self::VEGETARIAN_TASTE => 'Вегетарианская',
        self::EXOTIC_TASTE => 'Экзотическая',
    ];

    public static array $meat = [
        self::CHICKEN_MEAT => 'Курица',
        self::PORK_MEAT => 'Свинина',
        self::BEEF_MEAT => 'Говядина',
    ];

    public static array $pizzas = [
        'Маргарита',
        'Салями',
        'Ананасовая',
        '4 сыра',
    ];
}
