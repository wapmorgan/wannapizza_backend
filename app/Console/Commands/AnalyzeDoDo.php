<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AnalyzeDoDo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:dodo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $file = __DIR__.'/../../../storage/app/dodo.json';
        $data = json_decode(file_get_contents($file));
        $data = $data->menu;

        $pizzas_by_ids = [];

        foreach ($data->pizzas as $i => $pizza) {
            echo ($i + 1).'. '.$pizza->name.PHP_EOL;
            $ingredients = null;
            foreach ($pizza->products as $product) {
                $menu_product = $product->menuProduct->product;
                $pizzas_by_ids[$menu_product->uuId] = $product;
                if ($product->dough == 2) {
                    continue;
                }
                echo '- '.($product->dough == 1 ? 'обычное' : 'тонкое').', '.$product->size->name.', '.$product->menuProduct->price->value.'Р'.PHP_EOL;
                if ($ingredients === null) $ingredients = $menu_product->ingredients;
            }

            echo implode(', ', array_map(
                static function ($ingredient) {
                    return ($ingredient->isRemovable ? '+/-' : null).$ingredient->name;
                },
                $ingredients)).PHP_EOL;
        }

        foreach ($data->combos as $i => $combo) {
            $pizzas_in_combo = [];
//            foreach ($combo->slots) {
//
//            }
            echo ($i+1).'. '.$combo->name.': '.$combo->price->value.'Р'.PHP_EOL;
        }
        return 0;
    }
}
