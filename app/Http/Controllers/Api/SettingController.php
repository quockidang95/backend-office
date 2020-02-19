<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
class SettingController extends Controller
{
    public function getconfig(){
        $setting = Setting::where('id', 1)->first();
        return response()->json($setting, 200);
    }
}
