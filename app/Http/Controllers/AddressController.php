<?php

namespace App\Http\Controllers;

use App\Address;
use App\City;
use App\Helpers\GoogleGeocodeHelper;
use App\Region;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function newAddress(Request $request)
    {
        $this->validate($request, [
            'longitude' => 'required',
            'latitude' => 'required'
        ]);

/*
 * можна було б зробити щоб перед цим запитом дивилось в базі чи нема схожих координат
 * але може бути багато варіантів які не збігатимуться у пошуку а результат буде той самий
 */

/*
 * тут має бути сервіс контейнер але я ще не розібрався як його правильно зробити
 */
        $addressParse = (new GoogleGeocodeHelper())
            ->getAddressByLatLng(
                $request->get('longitude', 0),
                $request->get('latitude', 0)
            );

        if ($addressParse['initialize']) {
            $address = Address::where('place_id', $addressParse['place_id'])->first();
            if (!$address) {

                $regionDb = Region::where('name', $addressParse['region'])->first();
                if (!$regionDb) {
                    $regionDb = Region::create(['name' => $addressParse['region']]);
                }

                $cityDb = City::where('name', $addressParse['city'])->first();
                if (!$cityDb) {
                    $cityDb = City::create(['name' => $addressParse['city'], 'region_id' => $regionDb->id]);
                }

                Address::create([
                    'longitude' => $addressParse['lng'],
                    'latitude'  => $addressParse['lat'],
                    'place_id'  => $addressParse['place_id'],
                    'name'      => $addressParse['formatted_address'],
                    'city_id'   => $cityDb->id,
                    'region_id' => $regionDb->id
                ]);

            }
            return response()->json(['status' => 'success'], 201);
        }

        return response()->json(['status' => 'fail'], 500);
    }
}