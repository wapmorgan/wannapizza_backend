<?php
namespace App\Http\Controllers;

use App\Models\Selector;

class GeoController extends Controller
{
    public function select()
    {
        $selector = new Selector();
        return $selector->getAllCities();
    }
}
