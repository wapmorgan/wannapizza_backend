<?php
$city = 'moscow';
$url = 'https://dodopizza.ru/'.$city;

echo 'Starting request ...';
$time = microtime(true);
$content = file_get_contents($url);
echo ' got in '.round(microtime(true) - $time, 3).PHP_EOL;

preg_match_all('~JSON\.parse\("(.*)"\)\;window~us', $content, $match);
$json_string = str_replace('\u0022', '"', $match[1][0]);
$size = strlen($json_string);
//$file = getenv('CRAWLER_DATA_DIR').'/dodo.'.$city.'.json';
$file = '/var/crawler/data/dodo.'.$city.'.json';

echo 'Saving '.$size.' byte(s) to '.$file.' ...';
$written = file_put_contents($file, $json_string);

echo ' written '.$written.PHP_EOL;

if ($written !== $size) {
    exit (1);
}
return 0;
