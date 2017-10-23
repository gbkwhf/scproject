<?php

namespace App\TraitCollections;

use Illuminate\Http\Request;

trait ShareDataTrait
{

    public function getDoctorApplyState($user_id)
    {
        $clinic_doctor_apply = \DB::table('sky_clinic_doctor_apply')
            ->where('user_id',$user_id)
            ->pluck('apply_state');

        if($clinic_doctor_apply ===null) return -1;

        return $clinic_doctor_apply;

    }

    public function isPrivateDoctor($user_id)
    {

        $doctor = \DB::table('sky_clinic_employee_info')
            ->where('employee_id',$user_id)
            ->where('type',1)
            ->pluck('clinic_id');

        return $doctor === null ? false : true;

    }

    public function isShopkeeper($user_id)
    {
        $shopkeeper = \DB::table('sky_clinic_employee_info')
            ->where('employee_id', $user_id)
            ->pluck('employee_power');

        return $shopkeeper == 1000 ? true : false;

    }

    public function getClinicIdUserId($user_id)
    {
        return  \DB::table('sky_clinic_employee_info')
            ->where('employee_id', $user_id)
            ->pluck('clinic_id');
    }

    public function hasGps(Request $request)
    {
        return $request->has('longitude') && $request->has('latitude');
    }
}