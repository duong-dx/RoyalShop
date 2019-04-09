<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ProductUpdateRequest extends FormRequest
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
            'name'=>'required|min:5|max:100',
            'slug'=>['required', Rule::unique('products')->ignore($this->id)],
            'code'=>['required', 'numeric', Rule::unique('products')->ignore($this->id)],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'brand_id' => ['required', 'numeric', 'exists:brands,id'],
            'category_id' => ['required', 'numeric', 'exists:categories,id'],
            'warranty_time'=>'required',
            'ram'=>'required',
            'weight'=>'required',
            'screen_size'=>'required',
            'pin'=>'required',
            'front_camera'=>'required',
            'rear_camera'=>'required',
            'operating_system'=>'required',
        ];
    }

    public function messages()
    {
        return [
        'name.required'=>'(*) Vui lòng nhập name !',
        'name.min'=>'(*) Name tối thiệu 5 kí tự !',
        'name.max'=>'(*) Name tối đa 100 kí tự !',
        'slug.required'=> '(*) Vui lòng nhập vào slug !',
        'slug.unique'=> '(*) Slug đã được sử dụng !',
        'code.required'=> '(*) Vui lòng nhập vào code !',
        'code.numeric'=> '(*) Code phải là số !',
        'code.unique'=> '(*) Code đã được sử dụng !',
        'user_id.required'=> '(*) Vui lòng chọn nhân viên !',
        'user_id.numeric'=> '(*) Id nhân viên phải là số !',
        'user_id.exists'=> '(*) Nhân viên không tồn tại !',
        'brand_id.required'=> '(*) Vui lòng chọn thương hiệu !',
        'brand_id.numeric'=> '(*) Id thương hiệu phải là số !',
        'brand_id.exists'=> '(*) Thương hiệu không tồn tại !',
        'category_id.required'=> '(*) Vui lòng chọn loại sản phẩm !',
        'category_id.numeric'=> '(*) Id loại sản phẩm phải là số !',
        'category_id.exists'=> '(*) Loại sản phẩm không tồn tại !',
        'warranty_time.required'=>'(*) Vui lòng nhập thời gian bảo hành !',
        'ram.required'=>'(*) Vui lòng nhập Ram của máy !',
        'weight.required'=>'(*) Vui lòng nhập cân nặng của máy !',
        'screen_size.required'=>'(*) Vui lòng nhập kích thước màn hình !',
        'pin.required'=>'(*) Vui lòng nhập dung lượng pin !',
        'front_camera.required'=>'(*) Vui lòng nhập độ phân giải camera trước !',
        'rear_camera.required'=>'(*) Vui lòng nhập độ phân giải camera sau !',
        'operating_system.required'=>'(*) Vui lòng nhập hệ điều hành !',
        ];
    }
}
