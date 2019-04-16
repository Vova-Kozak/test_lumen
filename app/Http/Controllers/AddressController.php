<?php

namespace App\Http\Controllers;

use App\Address;
use App\City;
use App\Helpers\GoogleGeocodeHelper;
use App\Region;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /** @var GoogleGeocodeHelper $googleGeocodeHelper */
    private $googleGeocodeHelper;

    public function __construct(GoogleGeocodeHelper $googleGeocodeHelper)
    {
        $this->googleGeocodeHelper = $googleGeocodeHelper;
    }

    public function newAddress(Request $request)
    {
        $this->validate($request, [
            'longitude' => 'required',
            'latitude' => 'required'
        ]);

/*
 * можна було б зробити щоб перед цим запитом дивилось в базі чи нема схожих координат
 * але може бути багато вхідних варіантів координат які не збігатимуться у пошуку в базі, а результат з гугла буде той самий
 */

        $addressParse = $this->googleGeocodeHelper
            ->getAddressByLatLng(
                floatval($request->get('latitude', 0)),
                floatval($request->get('longitude', 0))
            );

        if ($addressParse['initialize']) {
            $address = Address::where('place_id', $addressParse['place_id'])->first();
            if (!$address) {

                if ($addressParse['region'] != '') {
                    $regionDb = Region::where('name', $addressParse['region'])->first();
                    if (!$regionDb) {
                        $regionDb = Region::create(['name' => $addressParse['region']]);
                    }
                }

                if ($addressParse['city'] != '') {
                    $cityDb = City::where('name', $addressParse['city'])->first();
                    if (!$cityDb) {
                        $cityDb = City::create(['name' => $addressParse['city'], 'region_id' => $addressParse['region'] ? $regionDb->id : null]);
                    }
                }

                Address::create([
                    'longitude' => $addressParse['lng'],
                    'latitude'  => $addressParse['lat'],
                    'place_id'  => $addressParse['place_id'],
                    'name'      => $addressParse['formatted_address'],
                    'city_id'   => $addressParse['city'] ? $cityDb->id : null,
                    'region_id' => $addressParse['region'] ? $regionDb->id : null
                ]);
            }

            return response()->json(['status' => 'success'], 201);
        }

        return response()->json(['status' => 'fail'], 500);
    }
}