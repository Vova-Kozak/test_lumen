<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function regionList(Request $request)
    {
        $this->validate($request, [
            'page' => 'int'
        ]);

        $page = $request->get('page', 1);
        $regionList = Region::all()->forPage($page, 20);
        return response($regionList, 200);
    }

    public function addressByRegion($id = 0)
    {
        $addressList = Region::with('cities')->find($id);

        return response($addressList ?? [], 200);
    }
}
