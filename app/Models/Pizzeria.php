<?php
namespace App\Models;

abstract class Pizzeria
{
    public static array $cities = [];
    public static array $citiesUnifications = [];

    abstract public function select(
        string $city,
        int $persons,
        ?array $tastes,
        ?array $meat,
        ?bool $vegetarianOnly,
        ?int $maxPrice
    );

    /**
     * @param array $pizzas
     * @param int $persons
     * @param array|null $tastes
     * @param array|null $meat
     * @param bool|null $vegetarianOnly
     * @param int|null $maxPrice
     * @return array
     */
    protected function filterPizzasByTastes(
        array $pizzas,
        ?array $tastes,
        ?array $meat,
        ?bool $vegetarianOnly
    )
    {
        $result = [];

        if ($tastes !== null) {
            $allowed_tastes = array_keys($tastes, true);
            $disallowed_tastes = array_keys($tastes, false);
        }

        if ($meat !== null) {
            $allowed_meat = array_keys($meat, true);
            $disallowed_meat = array_keys($meat, false);
        }

        foreach ($pizzas as $pizza) {
            if (!$this->passesFilters(
                $pizza->tastes, $pizza->meat,
                $allowed_tastes ?? null, $disallowed_tastes ?? null,
                $allowed_meat ?? null, $disallowed_meat ?? null,
                $vegetarianOnly))
                continue;

            $result[] = $pizza;
        }

        return $result;
    }

    protected function filterDough(array &$pizzas, array $doughs): void
    {
        foreach ($pizzas as $i => $pizza) {
            if (!in_array($pizza->dough, $doughs))
                unset($pizzas[$i]);
        }
    }

    /**
     * @param array $pizzaTastes
     * @param array $pizzaMeat
     * @param array|null $allowedTastes
     * @param array|null $disallowedTastes
     * @param array|null $allowedMeat
     * @param array|null $disallowedMeat
     * @param bool|null $vegetarianOnly
     * @return bool
     */
    protected function passesFilters(
        array $pizzaTastes,
        array $pizzaMeat,
        ?array $allowedTastes,
        ?array $disallowedTastes,
        ?array $allowedMeat,
        ?array $disallowedMeat,
        ?bool $vegetarianOnly): bool
    {
        if ($vegetarianOnly) {
            if (!empty($pizzaMeat) || !Menu::isVegetarianByTastes($pizzaTastes))
            return false;
        }

        if ($allowedTastes !== null
            && count(array_intersect($pizzaTastes, $allowedTastes)) !== count($allowedTastes))
            return false;
        if (isset($disallowedTastes) && !empty(array_intersect($pizzaTastes, $disallowedTastes)))
            return false;

        if (isset($allowedMeat)
            && count(array_intersect($pizzaMeat, $allowedMeat)) !== count($allowedMeat))
            return false;
        if (isset($disallowedMeat) && !empty(array_intersect($pizzaMeat, $disallowedMeat)))
            return false;

        return true;
    }

    public static function getUnifiedCities()
    {
        $cities = static::$cities;
        foreach (static::$citiesUnifications as $citySource => $cityDestination) {
            if (isset($cities[$citySource])) {
                $cities[$cityDestination] = $cities[$citySource];
                unset($cities[$citySource]);
            }
        }
        return $cities;
    }

    protected function getOriginalCity(string $city)
    {
        if (($key = array_search($city, static::$citiesUnifications)) !== false) {
            $city = $key;
        }
        return $city;
    }
}
