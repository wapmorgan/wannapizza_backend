<?php

$city = 'moscow';

$curl = curl_init('https://www.papajohns.ru/'.$city);

curl_setopt_array($curl, [
    CURLOPT_HTTPHEADER => [
        "Connection: closed",
        "Accept-Language: ru-RU,ru;q=0.9",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
        "DNT: 1",
        "Upgrade-Insecure-Requests: 1",
        "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36",
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_VERBOSE => true,
]);

echo 'Starting request ...';
$time = microtime(true);
$content = curl_exec($curl);
echo ' got in '.round(microtime(true) - $time, 3).PHP_EOL;

file_put_contents('/tmp/papa', $content);

preg_match_all('~window\.__PRELOADED_STATE__\s*\=\s*(\{.+\});\s*;?\<\/script\>~us', $content, $match);
//var_dump($match);
$json_string = str_replace('e=>e', 'null', $match[1][0]);
$size = strlen($json_string);
//$file = getenv('CRAWLER_DATA_DIR').'/dominos.'.$city.'.json';
$file = __DIR__.'/data/papajohns.'.$city.'.json';

echo 'Saving '.$size.' byte(s) to '.$file.' ...';
$written = file_put_contents($file, $json_string);

echo ' written '.$written.PHP_EOL;

if ($written !== $size) {
    exit (1);
}
return 0;
