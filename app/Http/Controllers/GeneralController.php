<?php

namespace App\Http\Controllers;

use App\Models\UserGeolocation;
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

    public function save_location(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        // $user_type = auth()->user()->user_type;
        $user_type = 'C';

        $allowed_user = ['D', 'C', 'O'];
        if (in_array($user_type, $allowed_user)) {
            $details = [];
            $model = UserGeolocation::where('user_id', auth()->user()->id)->first();
            if (!$model) {
                $model = new UserGeolocation();
                $model->user_id = auth()->user()->id;
                $model->user_type = $user_type;
                $details = array_merge($details, [['latitude' => $latitude, 'longitude' => $longitude]]);
                $model->detail_location = json_encode($details);
            } else {
                $details = json_decode($model->detail_location, true);
                $details = array_merge($details, [['latitude' => $latitude, 'longitude' => $longitude]]);
                $model->detail_location = json_encode($details);
            }
            $model->save();
        }
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
