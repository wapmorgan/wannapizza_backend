<?php
namespace App\Models;

use stdClass;

class DoDoPizzeria
{
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

        foreach ($pizzas as $pizza) {
            $result[] = [$pizza->name];
        }

        return $result;
    }
}
