<?php


namespace App\Helpers;


class GoogleGeocodeHelper
{
    private $googleApiKey;

    public function __construct()
    {
        $this->googleApiKey = env('GOOGLE_MAP_API_KEY');
    }

    public function getAddressByLatLng($longitude = 0, $latitude = 0)
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

        $latlng = implode(',', [$longitude, $latitude]);

        $ch = curl_init('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latlng . '&language=uk&key=' . $this->googleApiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

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
            }
        }

        return $resultData;
    }
}