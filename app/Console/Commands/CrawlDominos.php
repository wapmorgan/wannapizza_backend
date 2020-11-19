<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CrawlDominos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:dominos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl Dominos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $city = 'moscow';

        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: ru\r\n" .
                    "Cookie: selectedCityUrl=$city\r\n".
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36\r\n"
            )
        );

        $context = stream_context_create($opts);

        $url = 'https://dominospizza.ru/';

        echo 'Starting request ...';
        $time = microtime(true);
        $content = file_get_contents($url, false, $context);
        echo ' got in '.round(microtime(true) - $time, 3).PHP_EOL;

        preg_match_all('~window\.__PRELOADED_STATE__ \= (\{.+\})\s*\<\/script\>~us', $content, $match);
        $json_string = $match[1][0];
        $file = __DIR__.'/../../../storage/app/dominos.'.$city.'.json';
        file_put_contents($file, $json_string);

        return 0;
    }
}
