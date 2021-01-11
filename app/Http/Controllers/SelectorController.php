<?php
namespace App\Http\Controllers;

use App\Models\DoDoPizzeria;
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

        $do_do = new DoDoPizzeria();
        $pizzas = $do_do->select(
            'moscow', $validated['persons'],
            $validated['tastes'] ?? null, $validated['meat'] ?? null);

        return $pizzas;
    }
}
