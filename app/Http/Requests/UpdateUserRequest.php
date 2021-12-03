<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
//        dd($this);
        return [
            'name'=>'required|min:3',
            'username'=> 'required|min:3',Rule::unique('users','username')->ignore($this->get('_uid')),
            'email'=> 'required|email|unique:users,email,'.$this->get('_uid'),
            'position_id'=> 'required|exists:positions,id',
            'old_password'=> 'nullable',
            'password'=> ''
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email'=> 'Alamat Email',
            'position_id'=> 'Posisi',
        ];
    }
}
