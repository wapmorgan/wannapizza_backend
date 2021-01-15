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
