<?php
namespace App\Models;

use stdClass;

class DoDoPizzeria
{
    const STANDARD_DOUGH = 1;
    public static array $sizesInCm = [
        1 => 25,
        2 => 30,
        3 => 35,
    ];

    public static array $matchingSizesByPersons = [
        1 => [1],
        2 => [2],
        3 => [3],
        4 => [3],
        5 => [3],
        6 => [3],
        7 => [3],
        8 => [3],
        9 => [3],
        10 => [3],
    ];

    public static array $tastesMarkers = [
        Menu::CHEESE_TASTE => ['пармезана', 'пармезан', 'чеддера', 'чеддер', 'моцареллы', 'моцарелла', 'брынзы', 'брынза'],
        Menu::SAUSAGE_TASTE => ['пепперони'],
        Menu::SPICY_TASTE => ['острый'],
        Menu::EXOTIC_TASTE => ['яблоки', 'смородина', 'пломбир', 'корица', 'ананасы', 'брусника', 'молоко', 'сгущенное'],
    ];

    public function select(string $city, int $persons, ?array $tastes, ?array $meat)
    {
        $menu = $this->getMenu($city);
        $pizzas = $this->findPizzas($menu->pizzas, $persons, $tastes, $meat);
//        $combos = $this->findCombos($persons, $tastes, $meat);
        return $pizzas;
    }

    /**
     * @param string $city
     * @return mixed
     */
    protected function getMenu(string $city)
    {
        $file = __DIR__.'/../../storage/app/dodo.'.$city.'.json';
        $data = json_decode(file_get_contents($file));
        return $data->menu;
    }

    protected function findPizzas(array $pizzas, int $persons, ?array $tastes, ?array $meat)
    {
        $result = [];

        $matched_sizes = self::$matchingSizesByPersons[$persons];

        foreach ($pizzas as $pizza) {
            // Проверяем все размеры пиццы
            foreach ($pizza->products as $pizza_product) {
                // Не берём на тонком тесте
                if ($pizza_product->dough != self::STANDARD_DOUGH) continue;

                // Проверяем размер пиццы
                if (!in_array($pizza_product->sizeGroup, $matched_sizes)) continue;

//                if (!isset($pizza_product->ingredients)) var_dump($pizza_product);
                $product_tastes = $this->getTastesByIngredients($pizza_product->menuProduct->product->ingredients);
//                // Проверяем вкусы выбранные
//                if ($tastes !== null) {
//
//                }

                $last_image = last($pizza_product->menuProduct->product->productImages);

                $result[] = [
                    'pizzeria' => 'dodo',
                    'id' => $pizza_product->menuProduct->product->uuId,
                    'name' => $pizza->name,
                    'size' => self::$sizesInCm[$pizza_product->sizeGroup],
                    'tastes' => $product_tastes,
                    'price' => $pizza_product->menuProduct->price->value,
                    'thumbnail' => $last_image->url,
                ];
            }
        }

        return $result;
    }

    public function getTastesByIngredients(array $ingredients)
    {
        $ingredients = explode(' ',
            implode(' ',
                array_map(static function ($ingredient) {return mb_strtolower($ingredient->name);}, $ingredients)
            )
        );

        $tastes = [];
        foreach (self::$tastesMarkers as $taste => $tasteMarkers) {
            foreach ($tasteMarkers as $tasteMarker) {
                if (in_array($tasteMarker, $ingredients)) {
                    $tastes[] = $taste;
                    continue(2);
                }
            }
        }
        return $tastes;
    }
}
