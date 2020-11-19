<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CrawlDoDo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:dodo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl DoDo';

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
        $url = 'https://dodopizza.ru/'.$city;

        echo 'Starting request ...';
        $time = microtime(true);
        $content = file_get_contents($url);
        echo ' got in '.round(microtime(true) - $time, 3).PHP_EOL;

        preg_match_all('~JSON\.parse\("(.*)"\)\;window~us', $content, $match);
        $json_string = str_replace('\u0022', '"', $match[1][0]);
        $file = __DIR__.'/../../../storage/app/dodo.'.$city.'.json';
        file_put_contents($file, $json_string);

        return 0;
    }
}
