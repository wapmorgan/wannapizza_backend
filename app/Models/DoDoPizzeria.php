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

        $pizzas = $this->findPizzas($menu->pizzas, $persons, $tastes, $meat, $vegetarianOnly, $maxPrice);
        $combos = $this->findCombos($menu->combos, $pizzas, $persons, $tastes, $meat, $vegetarianOnly, $maxPrice);
//        var_dump($combos);
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
     * @param int $persons
     * @param array|null $tastes
     * @param array|null $meat
     * @param bool|null $vegetarianOnly
     * @param int|null $maxPrice
     * @return array
     */
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
                    'id' => $pizza->uuId,
                    'sizeId' => $pizza_product->menuProduct->product->uuId,
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

    protected function findCombos(
        array $combos,
        array $pizzas,
        int $persons,
        ?array $tastes,
        ?array $meat,
        ?bool $vegetarianOnly,
        ?int $maxPrice)
    {
        $result = [];
        $needed_for_1_person = 300;

        // подготовим данные по пиццам
        foreach ($pizzas as $i => $pizza) {
            unset($pizzas[$i]);
            $pizzas[$pizza['sizeId']] = $pizza;
        }

        // Проверяем совпадение комб
        foreach ($combos as $combo) {

        }
//        print_r($pizzas);exit;
    }
}
