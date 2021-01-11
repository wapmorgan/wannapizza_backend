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
     * @return array
     */
    public function select(string $city, int $persons, ?array $tastes, ?array $meat): array
    {
        $pizzas = [];

        foreach (self::$pizzerias[$city] as $pizzeriaClass) {
            $pizzeria_model = new $pizzeriaClass();
            foreach ($pizzeria_model->select($city, $persons, $tastes, $meat) as $found_pizza) {
                $pizzas[] = $found_pizza;
            }
        }

        return $pizzas;
    }
}
