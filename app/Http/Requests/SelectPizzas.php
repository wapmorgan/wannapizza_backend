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
        return [
            'persons' => ['required', 'numeric', Rule::in(range(1, 10))],
            'tastes' => ['array', Rule::in(array_keys(Menu::$tastes))],
            'pizzas' => ['array', Rule::in(array_keys(Menu::$pizzas))],
        ];
    }
}
