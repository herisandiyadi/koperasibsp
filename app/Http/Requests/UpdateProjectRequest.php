<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'region_id'=> 'required|exists:ms_regions,id',
            'project_name'=> 'required|min:3',
            'address'=>'required',
            'start_date'=> 'required',
            'end_date'=>'required',
            'region_code'=>'',
            'contract_number'=>'required',
            'total_member'=>'integer',
            'date_salary'=>'integer',
            'status'=>'required',
        ];
    }

    public function attributes()
    {
        return [
            'region_id' => 'Area',
            'project_name'=> 'Nama Project',
            'address'=> 'Alamat',
            'contract_number'=> 'Nomor Kontrak',
            'total_member'=> 'Total Anggota',
            'date_salary'=> 'Tanggal Penggajian',
            'start_date'=> 'Tanggal Mulai',
            'end_date'=>'Tanggal Berkahir'
        ];
    }
}
