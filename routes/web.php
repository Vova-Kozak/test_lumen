<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('address', ['uses' => 'AddressController@newAddress']);
$router->get('region', ['uses' => 'RegionController@regionList']);
$router->get('region/{id}/address', ['uses' => 'RegionController@addressByRegion']);
