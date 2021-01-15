<?php

$cities = [
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

function downloadCity($city)
{
    $opts = [
        'http' => [
            'method' => "GET",
            'header' => "Accept-language: ru\r\n" .
                "Cookie: selectedCityUrl=$city\r\n" .
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36\r\n"
        ]
    ];

    $context = stream_context_create($opts);

    $url = 'https://dominospizza.ru/';

    echo 'Downloading "'.$city.'" ...';
    $time = microtime(true);
    $content = file_get_contents($url, false, $context);
    echo ' got in ' . round(microtime(true) - $time, 3) . PHP_EOL;

    preg_match_all('~window\.__PRELOADED_STATE__ \= (\{.+\})\s*\<\/script\>~us', $content, $match);
    $json_string = $match[1][0];
    $size = strlen($json_string);
    //$file = getenv('CRAWLER_DATA_DIR').'/dominos.'.$city.'.json';
    $file = __DIR__.'/data/dominos.' . $city . '.json';

    echo 'Saving ' . $size . ' byte(s) to ' . $file . ' ...';
    $written = file_put_contents($file, $json_string);

    echo ' written ' . $written . PHP_EOL;

    if ($written !== $size) {
        throw new \RuntimeException('Too few bytes written');
    }
}

foreach ($cities as $city => $city_label)
{
    downloadCity($city);
    sleep(5);
}

return 0;
