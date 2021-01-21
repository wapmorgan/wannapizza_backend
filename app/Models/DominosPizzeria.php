<?php
namespace App\Models;

use Illuminate\Support\Facades\Log;

class DominosPizzeria extends Pizzeria
{
    public const PIZZERIA = 'dominos';

    public static array $sizesInCm = [
        9 => 20,
        12 => 28,
        14 => 33,
    ];

    public static array $doughValues = [
        'ULTRA' => Pizza::DOUGH_ULTRA,
        'THIN' => Pizza::DOUGH_THIN,
        'CLASSIC' => Pizza::DOUGH_NORMAL,
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

    public static array $citiesUnifications = [
        'dolgoprudny' => 'dolgoprudniy',
        'zheleznodorozhny' => 'zheldor',
        'naro-fominsk' => 'narofominsk',
        'odincovo' => 'odintsovo',
        'rostov-na-donu' => 'rostovnadonu',
        'sergiev-posad' => 'sergievposad',
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

        $original_city = $this->getOriginalCity($city);
        $pizzas_menu = $this->getPizzasMenu($original_city);
        $combos_menu = $this->getCombosMenu($original_city);

        $all_pizzas = $this->convertPizzas($pizzas_menu);
        $pizzas = $this->filterPizzasByTastes($all_pizzas, $tastes, $meat, $vegetarianOnly);
        $combos = $this->findCombos($combos_menu, $pizzas);

        $this->filterDough($pizzas, [Pizza::DOUGH_NORMAL]);

        return array_merge($pizzas, $combos);
    }

    /**
     * @param string $city
     * @return mixed
     */
    protected function getPizzasMenu(string $city)
    {
        $file = __DIR__.'/../../storage/app/dominos.'.$city.'.json';
        if (!file_exists($file)) {
            return [];
        }

        $data = json_decode(file_get_contents($file));
        return $data->pizza->list;
    }

    /**
     * @param string $city
     * @return mixed
     */
    protected function getCombosMenu(string $city)
    {
        $file = __DIR__.'/../../storage/app/dominos.'.$city.'.combo.json';
        if (!file_exists($file)) {
            return [];
        }

        try {
            $data = json_decode(file_get_contents($file), false, 512,);
            return $data->catalog->coupons->coupons;
        } catch (\JsonException $e) {
            Log::error($e->getMessage());
            return [];
        }
    }

    /**
     * @param $menu
     * @return array
     */
    protected function convertPizzas(
        $menu
    ): array
    {
        $pizzas = [];

        foreach ($menu as $pizza)
        {
            // Проверяем что пицца активна
            if (!$pizza->isActive) continue;

            $pizza_ingredients = array_map(static function ($ingredient) {return mb_strtolower($ingredient->description);}, $pizza->productOsgOptions);
            $pizza_ingredient_words = explode(' ', implode(' ', $pizza_ingredients));

            $pizza_tastes = Menu::getTastesByIngredients($pizza_ingredient_words);
            $pizza_meat = Menu::getMeatByIngredients($pizza_ingredient_words);

            // Размер пиццы
            foreach ($pizza->sizes as $pizza_size)
            {
                $pizza_diameter = self::$sizesInCm[$pizza_size->sizeCode];
                $pizza_area = M_PI * ($pizza_diameter / 2) ** 2;

                // Толщина теста
                foreach ($pizza_size->doughs as $pizza_size_dough)
                {
//                    // Проверка теста
//                    if ($pizza_size_dough->doughCode != self::STANDARD_DOUGH) continue;

                    // Тип борта
                    foreach ($pizza_size_dough->sides as $pizza_size_dough_side) {
                        $pizza_price = $pizza_size_dough_side->productPrice;

                        $pizzas[] = new Pizza([
                            'pizzeria' => self::PIZZERIA,
                            'id' => $pizza->id,
                            'sizeId' => $pizza_size_dough_side->productCode,
                            'dough' => self::$doughValues[$pizza_size_dough->doughCode],
                            'name' => $pizza->description,
                            'size' => $pizza_diameter,
                            'tastes' => $pizza_tastes,
                            'meat' => $pizza_meat,
                            'price' => $pizza_price,
                            'pizzaArea' => $pizza_area,
                            'pizzaCmPrice' => $pizza_price / $pizza_area,
                            // На удивление, у них динамическая ссылка опреедляет создаваемое изображение. Можно поставить свои размеры
                            'thumbnail' => 'https://dpr-cdn.azureedge.net/api/medium/ProductOsg/Global/' . $pizza->productOsgCode . '/NULL/300x300/RU',
                            'ingredients' => $pizza_ingredients,
                        ]);
                    }
                }
            }
        }

        return $pizzas;
    }

    /**
     * @param array $combos
     * @param Pizza[] $pizzas
     */
    protected function findCombos(array $combos, array $allPizzas)
    {
        $result = [];

        $matching_pizzas = [];

        // подготовим данные по пиццам
        foreach ($allPizzas as $i => $pizza) {
            unset($allPizzas[$i]);
            $allPizzas[$pizza->sizeId] = $pizza;
            $matching_pizzas[] = $pizza->sizeId;
        }

        // Проверяем совпадение комб
        foreach ($combos as $combo) {
            $combo_total_pizza_area = 0;
            $combo_total_price = 0;

            $combo_pizzas = [];
            foreach ($combo->couponProductGroups as $group_index => $group) {
                $group_matched = 0;
                $combo_total_price += $group->discountValue;

                foreach ($group->products as $group_product) {
                    if (isset($allPizzas[$group_product])) {
                        if ($group_matched++ === 0) {
                            $combo_total_pizza_area += $allPizzas[$group_product]->pizzaArea;
                        }
                        $combo_pizzas[$group_index][] = $allPizzas[$group_product];
                        continue;
                    }
                }

                // Не убираем пока комбы с чем-то кроме пиццы
//                if ($group_matched === 0)
//                    continue(2);
            }

            if ($combo_total_pizza_area === 0)
                continue;

            if ($combo_total_price === 0)
                continue;

            $result[] = new Combo([
                'pizzeria' => self::PIZZERIA,
                'id' => $combo->couponUrl,
                'name' => $combo->description,
                'price' => $combo_total_price,
                'pizzaArea' => $combo_total_pizza_area,
                'pizzaCmPrice' => $combo_total_price / $combo_total_pizza_area,
                'slots' => $combo_pizzas,
                'thumbnail' => 'https://dpr-cdn.azureedge.net/api/medium/Coupon/Global/'.$combo->id.'/NULL/270x270/RU',
            ]);
        }
//        echo json_encode($result);exit;
        return $result;
    }
}
