<?php
namespace App\Http\Controllers;

use App\Models\DoDoPizzeria;
use App\Models\Selector;
use Illuminate\Http\Request;

class SelectorController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function lists(Request $request) {
        return [
            'persons' => range(1, 10),
            'tastes' => \App\Models\Menu::$tastes,
            'meat' => \App\Models\Menu::$meat,
            'pizzas' => \App\Models\Menu::$pizzas,
        ];
    }

    /**
     * @param \App\Http\Requests\SelectPizzas $request
     * @return int[]
     */
    public function select(\App\Http\Requests\SelectPizzas $request) {
        $validated = $request->validated();
//        return $validated;

        $selector = new Selector();
        $pizzas = $selector->select(
            $validated['city'], $validated['persons'],
            $validated['tastes'] ?? null, $validated['meat'] ?? null,
            $validated['vegetarian'] ?? false, $validated['maxPrice'] ?? null);

        return $pizzas;
    }

    public function goTo(Request $request)
    {
        $pizza_id = $request->query->get('pizzaId');
        switch ($request->query->get('pizzeria')) {
            case 'dodo':
                return redirect()->away('https://dodopizza.ru/moscow?product='.$pizza_id.'#pizzas');

            case 'dominos':
                return redirect()->away('https://dominospizza.ru/category/picca');
        }
    }
}
