<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TinNhanCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
        'id'            => 'required|exists:nhan_viens,id',
        'noi_dung'      => 'required|min:1|max:200'
       ];

    }
    public function messages()
    {
        return [
            'id.required'               => 'Cần chọn người để nhắn tin',
            'id.exists'                 => 'Người nhận tin nhắn không tồn tại',
            'noi_dung.required'         => 'Nội dung không được để Trống',
            'noi_dung.min'              => 'Nội dung ít nhất 1 kí tự',
            'noi_dung.max'              => 'Nội dung nhiều nhất 200 kí tự',

        ];
    }
}
