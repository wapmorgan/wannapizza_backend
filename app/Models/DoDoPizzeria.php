<?php
namespace App\Models;

class DoDoPizzeria extends Pizzeria
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
     * @param bool|null $vegetarianOnly
     * @param int|null $maxPrice
     * @return array
     */
    public function select(string $city, int $persons, ?array $tastes, ?array $meat, ?bool $vegetarianOnly, ?int $maxPrice)
    {
        $menu = $this->getMenu($city);
        $pizzas = $this->findPizzas($menu->pizzas, $persons, $tastes, $meat, $vegetarianOnly, $maxPrice);
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

    protected function findPizzas(
        array $pizzas,
        int $persons,
        ?array $tastes,
        ?array $meat,
        ?bool $vegetarianOnly,
        ?int $maxPrice
    )
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

                $pizza_price = $pizza_product->menuProduct->price->value;

                if ($maxPrice !== null && $pizza_price > $maxPrice)
                    continue;

                $pizza_ingredients = array_map(static function ($ingredient) {return mb_strtolower($ingredient->name);},
                    $pizza_product->menuProduct->product->ingredients);

                $pizza_ingredient_words = explode(' ', implode(' ', $pizza_ingredients));

                // Проверяем состав пиццы
                $pizza_tastes = Menu::getTastesByIngredients($pizza_ingredient_words);
                $pizza_meat = Menu::getMeatByIngredients($pizza_ingredient_words);

//                var_dump($vegetarianOnly, $pizza_tastes);

                if (!$this->passesFilters(
                    $pizza_tastes, $pizza_meat,
                    $allowed_tastes ?? null, $disallowed_tastes ?? null,
                    $allowed_meat ?? null, $disallowed_meat ?? null,
                    $vegetarianOnly))
                    continue;

                // Подборка картинки с размером примерно 300x300
                foreach ($pizza_product->menuProduct->product->productImages as $productImage) {
                    if ($productImage->size == 4) {
                        $product_image = $productImage;
                        break;
                    }
                }
                // Если не найдена, берём последнюю
                if (!isset($product_image))
                    $product_image = last($pizza_product->menuProduct->product->productImages);

                $pizza_diameter = self::$sizesInCm[$pizza_product->sizeGroup];

                $result[] = [
                    'pizzeria' => 'dodo',
                    'id' => $pizza->uuId, // $pizza_product->menuProduct->product->uuId,
                    'name' => $pizza->name,
                    'size' => $pizza_diameter,
                    'tastes' => $pizza_tastes,
                    'meat' => $pizza_meat,
                    'price' => $pizza_price,
                    'cmPrice' => $pizza_price / (M_PI * ($pizza_diameter / 2)^2),
                    'thumbnail' => $product_image->url,
                    'ingredients' => $pizza_ingredients,
                ];

                unset($product_image);
            }
        }

        return $result;
    }
}
