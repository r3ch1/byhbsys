<?php

namespace App\Actions;

use App\Dtos\BaseDto;
use Illuminate\Support\Facades\Http;
use Log;

class GetWeatherDto extends BaseDto
{
    protected float $latitude;
    protected float $longitude;
}

class GetWeatherAction extends BaseAction
{
    protected array $validationRules = [
        'latitude' => 'required|between:-90,90',
        'longitude' => 'required|between:-180,180'
    ];
    public function handle(GetWeatherDto $data)
    {
        $this->validateData($data);
        $http = Http::baseUrl(config('weather.WEATHER_API_BASE_URL'))
        ->get('forecast', [
            'lat'     => $data->latitude,
            'lon'     => $data->longitude,
            'appid' => config('weather.WEATHER_API_KEY'),
        ]);
        dd($http->object());
    }
}
