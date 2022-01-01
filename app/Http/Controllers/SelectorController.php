<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Selector;
use Illuminate\Http\Request;

class SelectorController extends Controller
{
    /**
     * Возвращает список фильтров
     * @param Request $request
     * @return array
     */
    public function lists(Request $request) {
        return [
//            'persons' => range(1, 15),
            'persons' => array_keys(Menu::$personsList),
            'tastes' => Menu::$tastes,
            'meat' => Menu::$meat,
            'pizzas' => Menu::$pizzas,
        ];
    }

    /**
     * Производит подбор пицц по критериям
     * @param \App\Http\Requests\SelectPizzas $request
     * @return int[]
     */
    public function select(\App\Http\Requests\SelectPizzas $request) {
        $validated = $request->validated();

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
        $city = $request->query->get('city');

        $type = $request->query->get('type');

        switch ($request->query->get('pizzeria')) {
            case 'dodo':
                return redirect()->away('https://dodopizza.ru/'.$city.'?product='.$pizza_id.'#'.($type === 'combo' ? 'combos' : 'pizzas'));

            case 'dominos':
                return redirect()->away($type === 'combo'
                    ? 'https://dominospizza.ru/product/'.$pizza_id
                    : 'https://dominospizza.ru/category/picca');
        }
    }
}
