<?php
namespace App\Models;

class Menu
{
    public const CHEESE_TASTE = 'cheese';
    public const SAUSAGE_TASTE = 'sausage';
    public const SPICY_TASTE = 'spicy';
//    public const VEGETARIAN_TASTE = 'vegetarian';
    public const MUSHROOM_TASTE = 'mushroom';
    public const EXOTIC_TASTE = 'exotic';

    public static array $personsList = [
        '1' => [1, 2],
        '2' => [2, 3],
        '3' => [3, 4],
        '4' => [4, 5],
        '5' => [5, 6],
        '6-8' => [6, 8],
        '8-10' => [8, 10],
        '10-15' => [10, 15],
        '>15' => [15, 100],
    ];

    public const CHICKEN_MEAT = 'chicken';
    public const PORK_MEAT = 'pork';
    public const BEEF_MEAT = 'beef';
    public const FISH_MEAT = 'fish';
    public const SAUCE_MEAT = 'sauce_meat';

    public static array $tastes = [
        self::CHEESE_TASTE => 'Сырная',
        self::SAUSAGE_TASTE => 'Колбасная',
        self::SPICY_TASTE => 'Острая',
//        self::VEGETARIAN_TASTE => 'Вегетарианская',
        self::MUSHROOM_TASTE => 'Грибная',
        self::EXOTIC_TASTE => 'Экзотическая',
    ];

    public static array $meat = [
        self::CHICKEN_MEAT => 'Курица',
        self::PORK_MEAT => 'Свинина',
        self::BEEF_MEAT => 'Говядина',
        self::FISH_MEAT => 'Рыба',
        self::SAUCE_MEAT => 'Мясной соус',
    ];

    public static array $meatTastes = [
        self::SAUSAGE_TASTE,
    ];

    public static array $pizzas = [
        'Маргарита',
        'Салями',
        'Ананасовая',
        '4 сыра',
    ];

    public static array $tastesMarkers = [
        Menu::CHEESE_TASTE => ['пармезана', 'пармезан', 'чеддера', 'чеддер', 'моцареллы', 'моцарелла', 'брынзы', 'брынза'],
        Menu::SAUSAGE_TASTE => ['пепперони', 'чоризо'],
        Menu::SPICY_TASTE => ['острый', 'острая', 'халапеньо'],
        Menu::MUSHROOM_TASTE => ['грибы', 'шампиньоны'],
        Menu::EXOTIC_TASTE => ['яблоки', 'смородина', 'пломбир', 'корица', 'ананасы', 'ананас', 'брусника', 'молоко', 'сгущенное', 'карамельный'],
    ];

    public static array $meatMarkers = [
        Menu::PORK_MEAT => ['свинина', 'ветчина', 'бекон'],
        Menu::CHICKEN_MEAT => ['курица', 'цыплёнок', 'цыпленок'],
        Menu::BEEF_MEAT => ['говядина'],
        Menu::FISH_MEAT => ['лосось'],
        Menu::SAUCE_MEAT => ['болоньезе'],
    ];

    /**
     * @param array $ingredients
     * @return array
     */
    public static function getTastesByIngredients(array $ingredients): array
    {
        $tastes = [];
        foreach (Menu::$tastesMarkers as $taste => $tasteMarkers) {
            foreach ($tasteMarkers as $tasteMarker) {
                if (in_array($tasteMarker, $ingredients)) {
                    $tastes[] = $taste;
                    continue(2);
                }
            }
        }
        return $tastes;
    }

    /**
     * @param array $ingredients
     * @return array
     */
    public static function getMeatByIngredients(array $ingredients): array
    {
        $meat_kinds = [];
        foreach (Menu::$meatMarkers as $meat => $meatMarkers) {
            foreach ($meatMarkers as $meatMarker) {
                if (in_array($meatMarker, $ingredients)) {
                    $meat_kinds[] = $meat;
                    continue(2);
                }
            }
        }
        return $meat_kinds;
    }

    /**
     * @param array $tastes
     * @return bool
     */
    public static function isVegetarianByTastes(array $tastes): bool
    {
        return empty(array_intersect($tastes, static::$meatTastes));
    }
}
