<?php


namespace App\Helpers;


class GoogleGeocodeHelper
{
    private $googleApiKey;

    public function __construct()
    {
        $this->googleApiKey = env('GOOGLE_MAP_API_KEY');
    }

    public function getAddressByLatLng($latitude = 0, $longitude = 0)
    {
        $resultData = [
            'place_id' => '',
            'formatted_address' => '',
            'lat' => '',
            'lng' => '',
            'region' => '',
            'city' => '',
            'initialize' => false
        ];

        $latlng = implode(',', [$latitude, $longitude]);

        $ch = curl_init('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latlng . '&language=uk&key=' . $this->googleApiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if ($result['status'] == 'OK') {
            if (!empty($result['results'])) {
                $firstResult = $result['results'][0];

                $resultData['initialize']        = true;
                $resultData['place_id']          = $firstResult['place_id'];
                $resultData['formatted_address'] = $firstResult['formatted_address'];
                $resultData['lat']               = $firstResult['geometry']['location']['lat'];
                $resultData['lng']               = $firstResult['geometry']['location']['lng'];

                foreach ($firstResult['address_components'] as $address_component) {
                    if (in_array('administrative_area_level_1', $address_component['types'])) {
                        $resultData['region'] = $address_component['long_name'];
                    }
                    if (in_array('locality', $address_component['types'])) {
                        $resultData['city'] = $address_component['long_name'];
                    }
                }

                if ($resultData['region'] == '') {
                    $resultData['region'] = $this->getRegionByLatLng($latlng);
                }

                if ($resultData['city'] == '') {
                    $resultData['city'] = $this->getCityByLatLng($latlng);
                }
            }
        }

        return $resultData;
    }

    private function getRegionByLatLng($latLng)
    {
        $ch = curl_init('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latLng . '&language=uk&result_type=administrative_area_level_1&key=' . $this->googleApiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        $region = 'without-region';

        if ($result['status'] == 'OK') {
            if (!empty($result['results'])) {
                foreach ($result['results'][0]['address_components'] as $address_component) {
                    if (in_array('administrative_area_level_1', $address_component['types'])) {
                        $region = $address_component['long_name'];
                    }
                }
            }
        }

        return $region;
    }

    private function getCityByLatLng($latLng)
    {
        $ch = curl_init('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latLng . '&language=uk&result_type=locality&key=' . $this->googleApiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        $city = 'without-city';

        if ($result['status'] == 'OK') {
            if (!empty($result['results'])) {
                foreach ($result['results'][0]['address_components'] as $address_component) {
                    if (in_array('locality', $address_component['types'])) {
                        $city = $address_component['long_name'];
                    }
                }
            }
        }

        return $city;
    }
}