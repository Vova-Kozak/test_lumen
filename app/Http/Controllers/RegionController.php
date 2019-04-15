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
        $regionList = Region::all()->forPage($page, 10);
        return response($regionList, 200);
    }

    public function addressByRegion(Request $request, $id = 0)
    {
        $this->validate($request, [
            'page' => 'int'
        ]);

        $page = $request->get('page', 1);
        $addressList = Region::find($id);
        $addressList = $addressList ? $addressList->addresses->forPage($page, 10) : [];

        return response($addressList, 200);

    }
}
