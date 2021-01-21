<?php
namespace App\Models;

use http\Env\Request;
use Illuminate\Support\Facades\Log;
use JsonException;

class DoDoPizzeria extends Pizzeria
{
    public const PIZZERIA = 'dodo';

    public static array $sizesInCm = [
        1 => 25,
        2 => 30,
        3 => 35,
    ];

    public static array $cities = [
        'moscow' => 'Москва',
        'peterburg' => 'Санкт-Петербург',

        'abakan' => 'Абакан',
        'abinsk' => 'Абинск',
        'adler' => 'Адлер',
        'aksay' => 'Аксай',
        'alexandrov' => 'Александров',
        'almetyevsk' => 'Альметьевск',
        'anapa' => 'Анапа',
        'angarsk' => 'Ангарск',
        'aprelevka' => 'Апрелевка',
        'arzamas' => 'Арзамас',
        'armavir' => 'Армавир',
        'arkhangelsk' => 'Архангельск',
        'astrakhan' => 'Астрахань',
        'achinsk' => 'Ачинск',
        'balakovo' => 'Балаково',
        'balashiha' => 'Балашиха',
        'baltiysk' => 'Балтийск',
        'barabinsk' => 'Барабинск',
        'barnaul' => 'Барнаул (Алтайский край)',
        'bataysk' => 'Батайск',
        'belgorod' => 'Белгород',
        'belogorsk' => 'Белогорск',
        'berdsk' => 'Бердск',
        'berezniki' => 'Березники',
        'blagoveschensk' => 'Благовещенск',
        'bratsk' => 'Братск',
        'bryansk' => 'Брянск',
        'bugulma' => 'Бугульма',
        'buzuluk' => 'Бузулук',
        'velikieluki' => 'Великие Луки',
        'velikiynovgorod' => 'Великий Новгород',
        'velsk' => 'Вельск',
        'vidnoe' => 'Видное',
        'vladivostok' => 'Владивосток',
        'vladikavkaz' => 'Владикавказ',
        'vladimir' => 'Владимир',
        'volgograd' => 'Волгоград',
        'volzhskii' => 'Волжский (Волгоградская обл.)',
        'vologda' => 'Вологда',
        'vorkuta' => 'Воркута',
        'voronezh' => 'Воронеж',
        'voskresensk' => 'Воскресенск',
        'votkinsk' => 'Воткинск',
        'vsevolozhsk' => 'Всеволожск',
        'vyborg' => 'Выборг',
        'vyshnyvolochyok' => 'Вышний Волочек',
        'vyazma' => 'Вязьма',
        'gatchina' => 'Гатчина',
        'gelendzhik' => 'Геленджик',
        'georgiyevsk' => 'Георгиевск',
        'glazov' => 'Глазов',
        'gornoaltaysk' => 'Горно-Алтайск',
        'grozniy' => 'Грозный',
        'gubkin' => 'Губкин',
        'gubkinskiy' => 'Губкинский',
        'dedovsk' => 'Дедовск',
        'dzerzhinsk' => 'Дзержинск',
        'dimitrovgrad' => 'Димитровград',
        'dmitrov' => 'Дмитров',
        'dolgoprudniy' => 'Долгопрудный',
        'domodedovo' => 'Домодедово',
        'drezna' => 'Дрезна',
        'dubna' => 'Дубна',
        'egoryevsk' => 'Егорьевск',
        'yeysk' => 'Ейск',
        'ekaterinburg' => 'Екатеринбург',
        'elabuga' => 'Елабуга',
        'essentuki' => 'Ессентуки',
        'zheleznogorsk' => 'Железногорск (Красноярский край)',
        'zheleznogorskkursk' => 'Железногорск (Курская обл.)',
        'zheldor' => 'Железнодорожный',
        'zhukovsky' => 'Жуковский',
        'zarechniy' => 'Заречный (Пензенская обл.)',
        'zelenogorskspb' => 'Зеленогорск (Санкт-Петербург)',
        'zelenograd' => 'Зеленоград',
        'zelenokumsk' => 'Зеленокумск',
        'zlatoust' => 'Златоуст',
        'ivanovo' => 'Иваново',
        'ivanteevka' => 'Ивантеевка',
        'izhevsk' => 'Ижевск',
        'irkutsk' => 'Иркутск',
        'istra' => 'Истра',
        'yoshkarola' => 'Йошкар-Ола',
        'kazan' => 'Казань',
        'kaliningrad' => 'Калининград',
        'kaluga' => 'Калуга',
        'kamenskuralskiy' => 'Каменск-Уральский',
        'kamenskshakhtinsky' => 'Каменск-Шахтинский',
        'kamyshin' => 'Камышин',
        'kansk' => 'Канск',
        'kashira' => 'Кашира',
        'kemerovo' => 'Кемерово',
        'kingisepp' => 'Кингисепп',
        'kirishi' => 'Кириши',
        'kirov' => 'Киров',
        'kirovsk' => 'Кировск (Лен. область)',
        'kislovodsk' => 'Кисловодск',
        'klimovsk' => 'Климовск',
        'klin' => 'Клин',
        'kovrov' => 'Ковров',
        'kogalym' => 'Когалым',
        'kolomna' => 'Коломна',
        'kolpino' => 'Колпино',
        'kommunarka' => 'Коммунарка',
        'komsomolsknaamure' => 'Комсомольск-на-Амуре',
        'konakovo' => 'Конаково',
        'kopeysk' => 'Копейск',
        'korenovsk' => 'Кореновск',
        'korolev' => 'Королев',
        'korsakov' => 'Корсаков',
        'kostroma' => 'Кострома',
        'kotlas' => 'Котлас',
        'krasnogorsk' => 'Красногорск',
        'krasnodar' => 'Краснодар',
        'krasnoeselo' => 'Красное Село',
        'krasnokamsk' => 'Краснокамск',
        'krasnoobsk' => 'Краснообск',
        'krasnoyarsk' => 'Красноярск',
        'krasnybor' => 'Красный Бор (Ленинградская обл.)',
        'kropotkin' => 'Кропоткин',
        'krymsk' => 'Крымск',
        'kstovo' => 'Кстово',
        'kuvandyk' => 'Кувандык',
        'kudrovo' => 'Кудрово',
        'kuybyshev' => 'Куйбышев',
        'kurgan' => 'Курган',
        'kurovskoye' => 'Куровское',
        'kursk' => 'Курск',
        'labytnangi' => 'Лабытнанги',
        'leninogorsk' => 'Лениногорск',
        'leninskkuznetskiy' => 'Ленинск-Кузнецкий',
        'likinodulyovo' => 'Ликино-Дулёво',
        'lipetsk' => 'Липецк',
        'lobnya' => 'Лобня',
        'lomonosov' => 'Ломоносов',
        'lyubertsy' => 'Люберцы',
        'lyantor' => 'Лянтор',
        'magadan' => 'Магадан',
        'magnitogorsk' => 'Магнитогорск',
        'maikop' => 'Майкоп',
        'mahachkala' => 'Махачкала',
        'miass' => 'Миасс',
        'mineralnyevody' => 'Минеральные Воды',
        'mirnyy' => 'Мирный (Архангельская обл.)',
        'mikhaylovsk' => 'Михайловск',
        'mozhaysk' => 'Можайск',
        'monino' => 'Монино',
        'moskovsky' => 'Московский',
        'muravlenko' => 'Муравленко',
        'murino' => 'Мурино',
        'murmansk' => 'Мурманск',
        'murom' => 'Муром',
        'mytishi' => 'Мытищи',
        'naberezhnyechelny' => 'Набережные Челны',
        'nadym' => 'Надым',
        'nalchik' => 'Нальчик',
        'narofominsk' => 'Наро-Фоминск',
        'nahabino' => 'Нахабино',
        'nakhodka' => 'Находка',
        'nevinnomyssk' => 'Невинномысск',
        'neftekamsk' => 'Нефтекамск',
        'nefteyugansk' => 'Нефтеюганск',
        'nizhnevartovsk' => 'Нижневартовск',
        'nizhnekamsk' => 'Нижнекамск',
        'nizhnynovgorod' => 'Нижний Новгород',
        'nizhnytagil' => 'Нижний Тагил',
        'nykolskoe' => 'Никольское',
        'novoaltaysk' => 'Новоалтайск',
        'novokuznetsk' => 'Новокузнецк',
        'novokuibyshevsk' => 'Новокуйбышевск',
        'novomoskovsk' => 'Новомосковск',
        'novorossiysk' => 'Новороссийск',
        'novosibirsk' => 'Новосибирск',
        'novouralsk' => 'Новоуральск',
        'novocheboksarsk' => 'Новочебоксарск',
        'novocherkassk' => 'Новочеркасск',
        'novyurengoy' => 'Новый Уренгой',
        'noginsk' => 'Ногинск',
        'norilsk' => 'Норильск',
        'noyabrsk' => 'Ноябрьск',
        'nyagan' => 'Нягань',
        'obninsk' => 'Обнинск',
        'odintsovo' => 'Одинцово',
        'oktyabrskiy' => 'Октябрьский (респ. Башкортостан)',
        'omsk' => 'Омск',
        'orel' => 'Орел',
        'orenburg' => 'Оренбург',
        'orehovozuevo' => 'Орехово-Зуево',
        'otradnoe' => 'Отрадное (Лен. обл.)',
        'pavlovsk' => 'Павловск',
        'pavlovskiyposad' => 'Павловский Посад',
        'pargolovo' => 'Парголово',
        'penza' => 'Пенза',
        'perm' => 'Пермь',
        'petergof' => 'Петергоф',
        'petrozavodsk' => 'Петрозаводск',
        'petropavlovskkamchatskiy' => 'Петропавловск-Камчатский',
        'podolsk' => 'Подольск',
        'pskov' => 'Псков',
        'pushkino' => 'Пушкино',
        'pyatigorsk' => 'Пятигорск',
        'raduzhnyi' => 'Радужный (Владимирская обл.)',
        'ramenskoe' => 'Раменское',
        'rasskazovka' => 'Рассказовка (Москва)',
        'reutov' => 'Реутов',
        'rzhev' => 'Ржев',
        'rostovnadonu' => 'Ростов-на-Дону',
        'rybinsk' => 'Рыбинск',
        'ryazan' => 'Рязань',
        'salavat' => 'Салават',
        'salehard' => 'Салехард',
        'samara' => 'Самара',
        'saransk' => 'Саранск',
        'sarapul' => 'Сарапул',
        'saratov' => 'Саратов',
        'sarov' => 'Саров',
        'severodvinsk' => 'Северодвинск',
        'seversk' => 'Северск',
        'sergievposad' => 'Сергиев Посад',
        'serov' => 'Серов',
        'serpuhov' => 'Серпухов',
        'sertolovo' => 'Сертолово',
        'sestroretsk' => 'Сестрорецк',
        'skolkovo' => 'Сколково',
        'slavyanka' => 'Славянка',
        'slavyansknakubani' => 'Славянск-на-Кубани',
        'smolensk' => 'Смоленск',
        'sovetskiy' => 'Советский',
        'solnechnogorsk' => 'Солнечногорск',
        'solileck' => 'Соль-Илецк',
        'sosnovybor' => 'Сосновый Бор',
        'sochi' => 'Сочи',
        'stavropol' => 'Ставрополь',
        'starayakupavna' => 'Старая Купавна',
        'stariyoskol' => 'Старый Оскол',
        'sterlitamak' => 'Стерлитамак',
        'strezhevoy' => 'Стрежевой',
        'stupino' => 'Ступино',
        'surgut' => 'Сургут',
        'syzran' => 'Сызрань',
        'syktyvkar' => 'Сыктывкар',
        'taganrog' => 'Таганрог',
        'tambov' => 'Тамбов',
        'tver' => 'Тверь',
        'tikhoretsk' => 'Тихорецк',
        'tobolsk' => 'Тобольск',
        'tolyatti' => 'Тольятти',
        'tomsk' => 'Томск',
        'torzhok' => 'Торжок',
        'tosno' => 'Тосно',
        'troitsk' => 'Троицк (г. Москва)',
        'tuapse' => 'Туапсе',
        'tuymazy' => 'Туймазы',
        'tula' => 'Тула',
        'tyumen' => 'Тюмень',
        'ulanude' => 'Улан-Удэ',
        'ulyanovka' => 'Ульяновка',
        'ulyanovsk' => 'Ульяновск',
        'usinsk' => 'Усинск',
        'usolyesibirskoe' => 'Усолье-Сибирское',
        'ussuriisk' => 'Уссурийск',
        'ufa' => 'Уфа',
        'uhta' => 'Ухта',
        'fryazino' => 'Фрязино',
        'khabarovsk' => 'Хабаровск',
        'khantymansiysk' => 'Ханты-Мансийск',
        'khimki' => 'Химки',
        'khotkovo' => 'Хотьково',
        'tchaikovsky' => 'Чайковский',
        'chapaevsk' => 'Чапаевск',
        'cheboksari' => 'Чебоксары',
        'chelyabinsk' => 'Челябинск',
        'cherepovets' => 'Череповец',
        'chehov' => 'Чехов',
        'chita' => 'Чита',
        'shatura' => 'Шатура',
        'shahty' => 'Шахты',
        'shlisselburg' => 'Шлиссельбург',
        'shushary' => 'Шушары',
        'shelkovo' => 'Щелково',
        'scherbinka' => 'Щербинка',
        'elektrostal' => 'Электросталь',
        'engels' => 'Энгельс',
        'yugorsk' => 'Югорск',
        'yuzhnosakhalinsk' => 'Южно-Сахалинск',
        'yablonovskiy' => 'Яблоновский',
        'yakutsk' => 'Якутск',
        'yaroslavl' => 'Ярославль',
    ];

    public static array $citiesUnifications = [
        'peterburg' => 'spb',
        'moskovsky' => 'moskovskiy',
        'mytishi' => 'mytishchi',
        'nizhnynovgorod' => 'nn',
        'khimki' => 'himki',
        'shelkovo' => 'schelkovo',
    ];

    public static array $doughTypes = [
        2 => Pizza::DOUGH_THIN,
        1 => Pizza::DOUGH_NORMAL,
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
        $original_city = $this->getOriginalCity($city);
        $menu = $this->getMenu($original_city);

        $pizzas = $this->filterPizzasByTastes($this->convertAllPizzas($menu->pizzas), $tastes, $meat, $vegetarianOnly);

        $combos = $this->findCombos($menu->combos, $pizzas);

        $this->filterDough($pizzas, [Pizza::DOUGH_NORMAL]);

        return array_merge($pizzas, $combos);
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
                'combos' => [],
            ];
        }

        try {
            $data = json_decode(file_get_contents($file), false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            Log::error($e->getMessage());
            return (object)[
                'pizzas' => [],
                'combos' => [],
            ];
        }
        return $data->menu;
    }

    /**
     * @param array $pizzas
     * @return array
     */
    protected function convertAllPizzas(array $pizzas): array
    {
        $result = [];
        foreach ($pizzas as $pizza) {
            // Проверяем все размеры пиццы
            foreach ($pizza->products as $pizza_product) {
//                // Не берём на тонком тесте
//                if ($pizza_product->dough != self::STANDARD_DOUGH) continue;

                $pizza_price = $pizza_product->menuProduct->price->value;

                $pizza_ingredients = array_map(static function ($ingredient) {return mb_strtolower($ingredient->name);},
                    $pizza_product->menuProduct->product->ingredients);

                $pizza_ingredient_words = explode(' ', implode(' ', $pizza_ingredients));

                // Проверяем состав пиццы
                $pizza_tastes = Menu::getTastesByIngredients($pizza_ingredient_words);
                $pizza_meat = Menu::getMeatByIngredients($pizza_ingredient_words);

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
                $pizza_area = M_PI * ($pizza_diameter / 2)**2;

                $result[] = new Pizza([
                    'pizzeria' => self::PIZZERIA,
                    'id' => $pizza->uuId,
                    'sizeId' => $pizza_product->menuProduct->product->uuId,
                    'name' => $pizza->name,
                    'size' => $pizza_diameter,
                    'tastes' => $pizza_tastes,
                    'meat' => $pizza_meat,
                    'price' => $pizza_price,
                    'pizzaArea' => $pizza_area,
                    'pizzaCmPrice' => $pizza_price / $pizza_area,
                    'thumbnail' => $product_image->url,
                    'ingredients' => $pizza_ingredients,
                ]);

                unset($product_image);
            }
        }
        return $result;
    }

    /**
     * @param array $combos
     * @param array $allPizzas
     * @param int $persons
     * @param array|null $tastes
     * @param array|null $meat
     * @param bool|null $vegetarianOnly
     * @param int|null $maxPrice
     */
    protected function findCombos(
        array $combos,
        array $allPizzas)
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
            $combo_slots_count = count($combo->slots);
            $combo_total_pizza_area = 0;

            $combo_pizzas = [];
            foreach ($combo->slots as $slot_index => $slot) {
                $slot_matched = 0;

                foreach ($slot->products as $slot_product) {
                    if (isset($allPizzas[$slot_product->id])) {
                        if ($slot_matched++ === 0) {
                            $combo_total_pizza_area += $allPizzas[$slot_product->id]->pizzaArea;
                        }
                        $combo_pizzas[$slot_index][] = $allPizzas[$slot_product->id];
                        break;
                    }
                }

                // Не убираем пока комбы с чем-то кроме пиццы
//                if ($slot_matched === 0)
//                    continue(2);
            }

            if ($combo_total_pizza_area === 0)
                continue;

            // Подборка картинки с размером примерно 300x300
            foreach ($combo->comboImages as $comboImage) {
                if ($comboImage->size == 4) {
                    $combo_image = $comboImage;
                    break;
                }
            }
            // Если не найдена, берём последнюю
            if (!isset($combo_image))
                $combo_image = last($combo->comboImages);

            $result[] = new Combo([
                'pizzeria' => self::PIZZERIA,
                'id' => $combo->id,
                'name' => $combo->name,
                'price' => $combo->price->value,
                'pizzaArea' => $combo_total_pizza_area,
                'pizzaCmPrice' => $combo->price->value / $combo_total_pizza_area,
                'slots' => $combo_pizzas,
                'thumbnail' => $combo_image->url,
            ]);
        }
//        print_r($result);exit;
        return $result;
    }
}
