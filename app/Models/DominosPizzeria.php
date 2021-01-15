<?php
namespace App\Models;

class DominosPizzeria extends Pizzeria
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

    public static array $cities = [
        'moscow' => 'Москва',
        'spb' => 'Санкт-Петербург',

        'andreevka' => 'Андреевка',
        'balashiha' => 'Балашиха',
        'vidnoe' => 'Видное',
        'voronezh' => 'Воронеж',
        'dzerzhinskiy' => 'Дзержинский',
        'dolgoprudny' => 'Долгопрудный',
        'domodedovo' => 'Домодедово',
        'zheleznodorozhny' => 'Железнодорожный',
        'zhukovsky' => 'Жуковский',
        'zelenograd' => 'Зеленоград',
        'ivanteevka' => 'Ивантеевка',
        'itkara' => 'Иткара',
        'kazan' => 'Казань',
        'kolomna' => 'Коломна',
        'kommunarka' => 'Коммунарка',
        'korolev' => 'Королёв',
        'kotelniki' => 'Котельники',
        'krasnogorsk' => 'Красногорск',
        'krasnodar' => 'Краснодар',
        'lipetsk' => 'Липецк',
        'lobnya' => 'Лобня',
        'lyubertsy' => 'Люберцы',
        'moskovskiy' => 'Московский',
        'mytishchi' => 'Мытищи',
        'naro-fominsk' => 'Наро-Фоминск',
        'nn' => 'Нижний Новгород',
        'noginsk' => 'Ногинск',
        'odincovo' => 'Одинцово',
        'podolsk' => 'Подольск',
        'putilkovo' => 'Путилково',
        'pushkino' => 'Пушкино',
        'ramenskoe' => 'Раменское',
        'reutov' => 'Реутов',
        'rostov-na-donu' => 'Ростов-на-Дону',
        'ryazan' => 'Рязань',
        'samara' => 'Самара',
        'sapronovo' => 'Сапроново',
        'saratov' => 'Саратов',
        'sergiev-posad' => 'Сергиев Посад',
        'tver' => 'Тверь',
        'troitsk' => 'Троицк',
        'fryazino' => 'Фрязино',
        'himki' => 'Химки',
        'chelyabinsk' => 'Челябинск',
        'schelkovo' => 'Щёлково',
        'elektrostal' => 'Электросталь',
        'yaroslavl' => 'Ярославль',
        'scherbinka' => 'Щербинка',
    ];

    protected const STANDARD_DOUGH = 'CLASSIC';

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
    )
    {
        $menu = $this->getMenu($city);

        $pizzas = $this->findPizzas($menu, $persons, $tastes, $meat, $vegetarianOnly, $maxPrice);
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
        if (!file_exists($file)) {
            return [];
        }

        $data = json_decode(file_get_contents($file));
        return $data->pizza->list;
    }

    protected function findPizzas(
        $menu,
        int $persons,
        ?array $tastes,
        ?array $meat,
        ?bool $vegetarianOnly,
        ?int $maxPrice
    )
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

            if (!$this->passesFilters(
                $pizza_tastes, $pizza_meat,
                $allowed_tastes ?? null, $disallowed_tastes ?? null,
                $allowed_meat ?? null, $disallowed_meat ?? null,
                $vegetarianOnly))
                continue;

            foreach ($pizza->sizes as $pizza_size)
            {
                // Проверяем размер пиццы
                if (!in_array($pizza_size->sizeCode, $matched_sizes)) continue;

                foreach ($pizza_size->doughs as $pizza_size_dough)
                {
                    // Проверка теста
                    if ($pizza_size_dough->doughCode != self::STANDARD_DOUGH) continue;

                    $first_side = current($pizza_size_dough->sides);

                    $pizza_price = $first_side->productPrice;

                    if ($maxPrice !== null && $pizza_price > $maxPrice)
                        continue;

                    $pizza_diameter = self::$sizesInCm[$pizza_size->sizeCode];

                    $pizzas[] = [
                        'pizzeria' => self::PIZZERIA,
                        'id' => $pizza->id,
                        'name' => $pizza->description,
                        'size' => $pizza_diameter,
                        'tastes' => $pizza_tastes,
                        'meat' => $pizza_meat,
                        'price' => $pizza_price,
                        'cmPrice' => $pizza_price / (M_PI * ($pizza_diameter / 2)^2),
                        // На удивление, у них динамическая ссылка опреедляет создаваемое изображение. Можно поставить свои размеры
                        'thumbnail' => 'https://dpr-cdn.azureedge.net/api/medium/ProductOsg/Global/'.$pizza->productOsgCode.'/NULL/300x300/RU',
                        'ingredients' => $pizza_ingredients,
                    ];

                }
            }
        }

        return $pizzas;
    }
}
