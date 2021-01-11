<?php

$city = 'moscow';

$opts = [
    'http' => [
        'method' => "GET",
        'header' => "Accept-language: ru\r\n" .
        "Cookie: selectedCityUrl=$city\r\n".
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36\r\n"
    ]
];

$context = stream_context_create($opts);

$url = 'https://dominospizza.ru/';

echo 'Starting request ...';
$time = microtime(true);
$content = file_get_contents($url, false, $context);
echo ' got in '.round(microtime(true) - $time, 3).PHP_EOL;

preg_match_all('~window\.__PRELOADED_STATE__ \= (\{.+\})\s*\<\/script\>~us', $content, $match);
$json_string = $match[1][0];
$size = strlen($json_string);
//$file = getenv('CRAWLER_DATA_DIR').'/dominos.'.$city.'.json';
$file = '/var/crawler/data/dominos.'.$city.'.json';

echo 'Saving '.$size.' byte(s) to '.$file.' ...';
$written = file_put_contents($file, $json_string);

echo ' written '.$written.PHP_EOL;

if ($written !== $size) {
    exit (1);
}
return 0;
