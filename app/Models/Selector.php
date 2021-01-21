<?php
namespace App\Models;

class Selector
{
    public static array $pizzerias = [
        'moscow' => [DoDoPizzeria::class, DominosPizzeria::class],
    ];

    public static array $allPizzerias = [
        'dodo' => DoDoPizzeria::class,
        'dominos' => DominosPizzeria::class,
    ];

    /**
     * @var int Кол-во см2 пиццы для одного человека
     */
    public const NEEDED_ONE_IN_CM = 300;

    /**
     * @param string $city
     * @param int $persons
     * @param array|null $tastes
     * @param array|null $meat
     * @param bool|null $vegetarianOnly
     * @param int|null $maxPrice
     * @return array
     */
    public function select(
        string $city,
        int $persons,
        ?array $tastes,
        ?array $meat,
        ?bool $vegetarianOnly,
        ?int $maxPrice
    ): array
    {
        $products = [];

        foreach ($this->getPizzeriasByCity($city) as $pizzeriaClass) {
            /** @var Pizzeria $pizzeria_model */
            $pizzeria_model = new $pizzeriaClass();
            foreach ($pizzeria_model->select($city, $persons, $tastes, $meat, $vegetarianOnly, $maxPrice) as $found_pizza) {
                $products[] = $found_pizza;
            }
        }

        $this->filterBySizeAndPrice($products, $persons, $maxPrice);
        $this->sortProducts($products);

        return $products;
    }

    /**
     * @param $city
     * @return array
     */
    protected function getPizzeriasByCity($city): array
    {
        $pizzerias = [];

        foreach (static::$allPizzerias as $pizzeriaClass)
        {
            $pizzeria_cities = $pizzeriaClass::getUnifiedCities();
            if (isset($pizzeria_cities[$city]))
                $pizzerias[] = $pizzeriaClass;
        }

        return $pizzerias;
    }

    /**
     * @return array
     */
    public function getAllCities(): array
    {
        $cities = [];

        foreach (static::$allPizzerias as $pizzeria)
        {
            $cities += $pizzeria::getUnifiedCities();
        }

        asort($cities);

        $cities = array_reverse($cities);

        foreach (['spb', 'moscow'] as $preferredCity) {
            if (isset($cities[$preferredCity])) {
                $city_label = $cities[$preferredCity];
                unset($cities[$preferredCity]);
                $cities[$preferredCity] = $city_label;
            }
        }

        return array_reverse($cities);
    }

    /**
     * @param Product[] $products
     * @param int $persons
     * @param int|null $maxPrice
     * @return array
     */
    protected function filterBySizeAndPrice(array &$products, int $persons, ?int $maxPrice): void
    {
        $size_minimum = Selector::NEEDED_ONE_IN_CM * $persons;

        $size_maximum = $size_minimum * 1.5;

        foreach ($products as $i => $product) {
            // Проверяем размер пиццы
            if ($product->pizzaArea >= $size_minimum && $product->pizzaArea <= $size_maximum
                && ($maxPrice === null || $product->price <= $maxPrice)) continue;

            unset($products[$i]);
        }
    }

    /**
     * @param array $products
     */
    protected function sortProducts(array &$products)
    {
        usort($products, static function (Product $a, Product $b) {
            if ($a->pizzaCmPrice != $b->pizzaCmPrice)
                return $a->pizzaCmPrice <=> $b->pizzaCmPrice;

            return $a->price <=> $b->price;
        });
    }
}
