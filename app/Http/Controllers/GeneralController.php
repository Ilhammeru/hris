<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    /**
     * Function to get city by province id
     * @param int province
     * @return Response
     */
    public function getCityByProvince(Request $request)
    {
        $province = $request->province;
        $city = \Indonesia::findProvince($province, ['cities']);
        return response()->json(['data' => $city, 'message' => 'Success get city']);
    }

    /**
     * Function to get district by city id
     * @param int province
     * @return Response
     */
    public function getDistrictByCity(Request $request)
    {
        $city = $request->city;
        $districts = \Indonesia::findCity($city, ['districts']);
        return response()->json(['data' => $districts, 'message' => 'Success get city']);
    }

    /**
     * Function to get village by district id
     * @param int province
     * @return Response
     */
    public function getVillageByDistrict(Request $request)
    {
        $district = $request->district;
        $villages = \Indonesia::findDistrict($district, ['villages']);
        return response()->json(['data' => $villages, 'message' => 'Success get city']);
    }
}
