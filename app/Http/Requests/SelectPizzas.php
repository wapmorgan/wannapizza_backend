<?php

namespace App\Http\Requests;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SelectPizzas extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tastes = array_combine(
            array_map(static function ($taste) {return 'tastes.'.$taste;}, array_keys(Menu::$tastes)),
            array_fill(0, count(Menu::$tastes), ['boolean'])
        );


        $meat = array_combine(
            array_map(static function ($meat) {return 'meat.'.$meat;}, array_keys(Menu::$meat)),
            array_fill(0, count(Menu::$meat), ['boolean'])
        );

        return array_merge([
            'persons' => ['required', 'numeric', Rule::in(range(1, 10))],
            'price_below' => ['numeric', Rule::in(range(1, 10))],
            'pizzas' => ['array', Rule::in(array_keys(Menu::$pizzas))],
        ], $tastes, $meat);
    }
}
