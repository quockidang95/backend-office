<?php

namespace App\Http\Controllers;

use Exception;
use App\Promotion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StorePromotionRequest;

class PromotionController extends Controller
{
    public function index(){

        $promotions = Promotion::simplePaginate(10);
        foreach ( $promotions as $promotion){
            $check = $this->checkStatus($promotion);

            if (!$check){
                $promotion->update([ 'status' => 'expired']);
            } else {
                $promotion->update([ 'status' => 'still']);
            }
        }
       
        return view('backend.promotion.index', compact('promotions'));
    }

    public function viewstore(){
        return view('backend.promotion.store');
    }

    public function store(StorePromotionRequest $request){

        try {
            $data = $request->all();
            $data['created_at'] = Carbon::now('Asia/Ho_Chi_Minh');

            $promotion = Promotion::create($data);

            session()->put('success', 'Thêm thành công');
            return redirect()->route('promotion.index');

        } catch (Exception $ex) {

            session()->put('error', $ex->getMessage());
            return redirect()->route('promotion.index');

        }
    }

    public function viewupdate($id) {

        $promotion = Promotion::find($id);

        return view('backend.promotion.update', compact('promotion'));
    }

    public function update(StorePromotionRequest $request, $id) {

        try {

            $promotion = Promotion::find($id)->update($request->all());

            session()->put('success', 'Cập nhật thành công');
            return redirect()->route('promotion.index');

        } catch (Exception $ex) {

            session()->put('error', $ex->getMessage());
            return redirect()->route('promotion.index');

        }
    }

    public function delete ($id){

        $checkDeletePromotion = Promotion::find($id)->delete();
       // dd($checkDeletePromotion);
        if ( $checkDeletePromotion ) {
            session()->put('success', 'Thêm thành công');
            return redirect()->route('promotion.index');
        }

        session()->put('error', 'Xoá thất bại! Vui lòng thử lại sau');
        return redirect()->route('promotion.index');

    }

    // repositories in promotion controller

    public function checkStatus(Promotion $promotion){

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        if ( $promotion->start_date < $now && $promotion->end_date > $now ) {
            return true;
        }

        return false;

    }

    // check Promotion Code
    public function checkPromotionCode( $promotion_code ) {
        $promotion = Promotion::where('promotion_code', $promotion_code)->first();

        if($promotion){
            return false;
        }

        return true;
    }
}
