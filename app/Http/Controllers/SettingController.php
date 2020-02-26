<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
class SettingController extends Controller
{
    public function index(){
        $setting = Setting::all();
        return view('backend.setting.index', compact('setting'));
    }
    public function update(Request $request, $id){
        $setting = Setting::find($id);
        $setting->discount_point = $request->discount_point;
        $setting->discount_user = $request->discount_user;
        $setting->save();
        return redirect(route('setting.index'));
    }
}
