<?php
namespace App\Models;

use stdClass;

class DoDoPizzeria
{
    public const PIZZERIA = 'dodo';

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

    protected const STANDARD_DOUGH = 1;

    /**
     * @param string $city
     * @param int $persons
     * @param array|null $tastes
     * @param array|null $meat
     * @return array
     */
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
        if (!file_exists($file)) {
            return (object)[
                'pizzas' => [],
            ];
        }

        $data = json_decode(file_get_contents($file));
        return $data->menu;
    }

    protected function findPizzas(array $pizzas, int $persons, ?array $tastes, ?array $meat)
    {
        $result = [];

        $matched_sizes = self::$matchingSizesByPersons[$persons];

        if ($tastes !== null) {
            $allowed_tastes = array_keys($tastes, true);
            $disallowed_tastes = array_keys($tastes, false);
        }

        if ($meat !== null) {
            $allowed_meat = array_keys($meat, true);
            $disallowed_meat = array_keys($meat, false);
        }

        foreach ($pizzas as $pizza) {
            // Проверяем все размеры пиццы
            foreach ($pizza->products as $pizza_product) {
                // Не берём на тонком тесте
                if ($pizza_product->dough != self::STANDARD_DOUGH) continue;

                // Проверяем размер пиццы
                if (!in_array($pizza_product->sizeGroup, $matched_sizes)) continue;

//                // Проверяем вкусы выбранные
//                if ($tastes !== null) {
//
//                }

                $pizza_ingredients = array_map(static function ($ingredient) {return mb_strtolower($ingredient->name);},
                    $pizza_product->menuProduct->product->ingredients);

                $pizza_ingredient_words = explode(' ', implode(' ', $pizza_ingredients));

                // Проверяем состав пиццы
                $pizza_tastes = Menu::getTastesByIngredients($pizza_ingredient_words);
                $pizza_meat = Menu::getMeatByIngredients($pizza_ingredient_words);

//                var_dump(array_intersect($pizza_tastes, $allowed_tastes));
                if (isset($allowed_tastes)
                    && count(array_intersect($pizza_tastes, $allowed_tastes)) !== count($allowed_tastes)) continue;
                if (isset($disallowed_tastes) && !empty(array_intersect($pizza_tastes, $disallowed_tastes))) continue;

                if (isset($allowed_meat) && !empty(array_diff($pizza_meat, $allowed_meat))) continue;
                if (isset($disallowed_meat) && !empty(array_intersect($pizza_meat, $disallowed_meat))) continue;

                $last_image = last($pizza_product->menuProduct->product->productImages);

                $result[] = [
                    'pizzeria' => 'dodo',
                    'id' => $pizza_product->menuProduct->product->uuId,
                    'name' => $pizza->name,
                    'size' => self::$sizesInCm[$pizza_product->sizeGroup],
                    'tastes' => $pizza_tastes,
                    'meat' => $pizza_meat,
                    'price' => $pizza_product->menuProduct->price->value,
                    'thumbnail' => $last_image->url,
                    'ingredients' => $pizza_ingredients,
                ];
            }
        }

        return $result;
    }
}
