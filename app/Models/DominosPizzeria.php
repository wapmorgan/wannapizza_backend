<?php
namespace App\Models;

class DominosPizzeria
{
    public const PIZZERIA = 'dominos';

    public static array $sizesInCm = [
        9 => 20,
        12 => 28,
        14 => 33,
    ];

    public static array $matchingSizesByPersons = [
        1 => [9],
        2 => [12],
        3 => [14],
        4 => [14],
        5 => [14],
        6 => [14],
        7 => [14],
        8 => [14],
        9 => [14],
        10 => [14],
    ];

    protected const STANDARD_DOUGH = 'CLASSIC';

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

        $pizzas = $this->findPizzas($menu, $persons, $tastes, $meat);
//        $combos = $this->findCombos($menu, $persons, $tastes, $meat);

        return $pizzas;
    }

    /**
     * @param string $city
     * @return mixed
     */
    protected function getMenu(string $city)
    {
        $file = __DIR__.'/../../storage/app/dominos.'.$city.'.json';
        $data = json_decode(file_get_contents($file));
        return $data->pizza->list;
    }

    protected function findPizzas($menu, int $persons, ?array $tastes, ?array $meat)
    {
        $pizzas = [];
        $matched_sizes = self::$matchingSizesByPersons[$persons];

        if ($tastes !== null) {
            $allowed_tastes = array_keys($tastes, true);
            $disallowed_tastes = array_keys($tastes, false);
        }

        if ($meat !== null) {
            $allowed_meat = array_keys($meat, true);
            $disallowed_meat = array_keys($meat, false);
        }

        foreach ($menu as $pizza)
        {
            // Проверяем что пицца активна
            if (!$pizza->isActive) continue;

            $pizza_ingredients = array_map(static function ($ingredient) {return mb_strtolower($ingredient->description);}, $pizza->productOsgOptions);
            $pizza_ingredient_words = explode(' ', implode(' ', $pizza_ingredients));

            // Проверяем состав пиццы
            $pizza_tastes = Menu::getTastesByIngredients($pizza_ingredient_words);
            $pizza_meat = Menu::getMeatByIngredients($pizza_ingredient_words);

            if (isset($allowed_tastes)
                && count(array_intersect($pizza_tastes, $allowed_tastes)) !== count($allowed_tastes)) continue;
            if (isset($disallowed_tastes) && !empty(array_intersect($pizza_tastes, $disallowed_tastes))) continue;

            if (isset($allowed_meat) && !empty(array_diff($pizza_meat, $allowed_meat))) continue;
            if (isset($disallowed_meat) && !empty(array_intersect($pizza_meat, $disallowed_meat))) continue;

            foreach ($pizza->sizes as $pizza_size)
            {
                // Проверяем размер пиццы
                if (!in_array($pizza_size->sizeCode, $matched_sizes)) continue;

                foreach ($pizza_size->doughs as $pizza_size_dough)
                {
                    // Проверка теста
                    if ($pizza_size_dough->doughCode != self::STANDARD_DOUGH) continue;

                    $first_side = current($pizza_size_dough->sides);

                    $pizzas[] = [
                        'pizzeria' => self::PIZZERIA,
                        'id' => $pizza->id,
                        'name' => $pizza->description,
                        'size' => self::$sizesInCm[$pizza_size->sizeCode],
                        'tastes' => $pizza_tastes,
                        'meat' => $pizza_meat,
                        'price' => $first_side->productPrice,
                        // На удивление, у них динамическая ссылка опреедляет создаваемое изображение. Можно поставить свои размеры
                        'thumbnail' => 'https://dpr-cdn.azureedge.net/api/medium/ProductOsg/Global/'.$pizza->productOsgCode.'/NULL/200x200/RU',
                        'ingredients' => $pizza_ingredients,
                    ];

                }
            }
        }

        return $pizzas;
    }
}
