<?php
namespace App\Models;

class Selector
{
    public static array $pizzerias = [
        'moscow' => [DoDoPizzeria::class, DominosPizzeria::class],
    ];

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
        $pizzas = [];

        foreach (self::$pizzerias[$city] as $pizzeriaClass) {
            /** @var Pizzeria $pizzeria_model */
            $pizzeria_model = new $pizzeriaClass();
            foreach ($pizzeria_model->select($city, $persons, $tastes, $meat, $vegetarianOnly, $maxPrice) as $found_pizza) {
                $pizzas[] = $found_pizza;
            }
        }

        $this->sortPizzas($pizzas);

        return $pizzas;
    }

    protected function sortPizzas(array &$pizzas)
    {
        usort($pizzas, static function (array $a, array $b) {
            if ($a['cmPrice'] != $b['cmPrice'])
                return $a['cmPrice'] <=> $b['cmPrice'];

            return $a['price'] <=> $b['price'];
        });
    }
}
