<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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

    public function rules()
    {
        return [
            'region_id'=> 'required|exists:ms_regions,id',
            'branch_code'=>'required|unique:ms_branchs,branch_code,'.\Request::instance()->id,
            'status'=>'required',
            'branch_name'=> 'required|min:3',
            'address'=>'required',
            'telp'=>'required|min:3',
        ];
    }

    public function attributes()
    {
        return [
            'region_id' => 'Area',
            'branch_name'=> 'Nama Cabang',
            'address'=> 'Alamat',
            'telp'=> 'Telepon',
            'branch_code'=>'Kode Cabang'
        ];
    }
}
