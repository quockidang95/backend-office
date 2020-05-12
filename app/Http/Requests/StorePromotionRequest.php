<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromotionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'promotion_code' => 'required|unique:App\Promotion,promotion_code',
            'start_date' => 'required|before_or_equal:end_date',
            'end_date' => 'required',
            'adjuster' => 'required|alpha_num',
        ];
    }

    public function messages(){
        return [
            'title.required' => 'Title không được để trống',
            'promotion_code.required' => 'Mã giảm không được để trống',
            'start_date.required' => 'Ngày bắt đầu không được để trống',
            'end_date.required' => 'Ngày kết thúc không được để trống',
            'adjuster.required' => 'Phần % không được để trống',
            'adjuster.alpha_num' => 'Trường này chỉ chấp nhận số',
            'promotion_code.unique' => 'Mã giảm giá này đã tồn tại',
            'start_date.before_or_equal' => 'Ngày bắt đầu và kết thúc không hợp lệ'
        ];
       
    }
}
