<?php

define('TIME_BETWEEN_CITIES', 5);
define('TIME_BEFORE_COUPONS', 5);

$cities = [
    'moscow' => 1,
    'spb' => 18,

    'balashiha' => 12,
    'bryansk' => 30,
    'vidnoe' => 11,
    'voronezh' => 25,
    'dzerzhinskiy' => 54,
    'dmitrov' => 53,
    'dolgoprudny' => 20,
    'domodedovo' => 21,
    'ekaterinburg' => 26,
    'zheleznodorozhny' => 42,
    'zhukovsky' => 10,
    'zelenograd' => 2,
    'ivanteevka' => 16,
    'itkara' => 39,
    'kazan' => 34,
    'kaluga' => 29,
    'kolomna' => 43,
    'kommunarka' => 58,
    'korolev' => 9,
    'kotelniki' => 24,
    'krasnogorsk' => 8,
    'krasnodar' => 19,
    'kursk' => 32,
    'lipetsk' => 60,
    'lobnya' => 40,
    'lyubertsy' => 7,
    'moskovskiy' => 56,
    'mytishchi' => 13,
    'naro-fominsk' => 57,
    'nn' => 35,
    'noginsk' => 44,
    'odincovo' => 23,
    'podolsk' => 3,
    'putilkovo' => 37,
    'pushkino' => 14,
    'ramenskoe' => 46,
    'reutov' => 4,
    'rostov-na-donu' => 22,
    'ryazan' => 28,
    'samara' => 45,
    'sapronovo' => 15,
    'saratov' => 48,
    'sergiev-posad' => 50,
    'serpuhov' => 36,
    'tver' => 27,
    'togliatti' => 47,
    'tula' => 33,
    'troitsk' => 55,
    'ufa' => 38,
    'fryazino' => 49,
    'himki' => 5,
    'chelyabinsk' => 51,
    'chehov' => 52,
    'schelkovo' => 6,
    'elektrostal' => 41,
    'yaroslavl' => 31,
    'temp1' => 59,
    'temp2' => 61,
    'scherbinka' => 62,
];

function extractPreloadedJson($html)
{
    preg_match_all('~window\.__PRELOADED_STATE__ \= (\{.+\})\s*\<\/script\>~us', $html, $match);
    return $match[1][0];
}

function makeRequest($city, $path = null)
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
    $url = 'https://dominospizza.ru/'.$path;

    return file_get_contents($url, false, $context);
}

function ensureWrite($fileName, $content)
{
    $size = strlen($content);
    echo 'Saving ' . $size . ' byte(s) to ' . $fileName . ' ...';
    $written = file_put_contents($fileName, $content);
    echo ' written ' . $written . PHP_EOL;

    if ($written !== $size) {
        throw new \RuntimeException('Too few bytes written');
    }
}

function downloadCity($city)
{
    $file = __DIR__.'/data/dominos.' . $city . '.json';
//    if (!file_exists($file)) {
        echo 'Downloading main menu "' . $city . '" ...';
        $time = microtime(true);
        $content = makeRequest($city);
        echo ' got in ' . round(microtime(true) - $time, 3) . PHP_EOL;

        $content = extractPreloadedJson($content);
        ensureWrite($file, $content);
        //$file = getenv('CRAWLER_DATA_DIR').'/dominos.'.$city.'.json';
        sleep(TIME_BEFORE_COUPONS);
//    } else {
//        $content = file_get_contents($file);
//    }

    echo 'Downloading combo menu "'.$city.'" ...';
    $data = json_decode($content, false, 512, false);

    $coupon = current($data->home->promotedCoupons);

    $time = microtime(true);
    $content = makeRequest($city, 'product/'.$coupon->couponUrl);
    echo ' got in ' . round(microtime(true) - $time, 3) . PHP_EOL;

    $content = extractPreloadedJson($content);
    $coupons_file = __DIR__.'/data/dominos.' . $city . '.combo.json';
    ensureWrite($coupons_file, $content);
}

foreach ($cities as $city => $city_label)
{
    downloadCity($city);
    sleep(TIME_BETWEEN_CITIES);
}

return 0;
