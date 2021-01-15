<?php
namespace App\Http\Controllers;

use App\Models\Selector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GeoController extends Controller
{
    public function detect(Request $request)
    {
        try {
            $ip = $request->ip();
            $data = $this->getDataByIp($ip);
            return [
                'city' => strtolower($data->city)
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());
            return [];
        }
    }

    public function select()
    {
        $selector = new Selector();
        return $selector->getAllCities();
    }

    protected function getDataByIp($ip)
    {
        $url = 'https://api.ipgeolocation.io/ipgeo?apiKey='.env('IP_GEOLOCATION_TOKEN').'&ip='.$ip;
        return json_decode(file_get_contents($url), false, 512, JSON_THROW_ON_ERROR);
    }
}
