<?php

namespace App\Http\Controllers;

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
            'pizzas' => \App\Models\Menu::$pizzas,
        ];
    }

    /**
     * @param \App\Http\Requests\SelectPizzas $request
     * @return int[]
     */
    public function select(\App\Http\Requests\SelectPizzas $request) {
        $validated = $request->validated();
        return [$validated];
    }
}
