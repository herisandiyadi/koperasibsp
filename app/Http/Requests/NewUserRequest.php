<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewUserRequest extends FormRequest
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
            'name'=>'required|min:3',
            'username'=> 'required|min:3|unique:users,username',
            'email'=> 'required|email|unique:users,email',
            'position_id'=> 'required|exists:positions,id',
            'region_id' => 'required|exists:ms_regions,id',
            'password'=> 'required|min:6|confirmed'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email'=> 'Alamat Email',
            'position_id'=> 'Posisi',
            'region_id' => 'Area'
        ];
    }
}
