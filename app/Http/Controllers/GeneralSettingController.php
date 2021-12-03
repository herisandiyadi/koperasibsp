<?php

namespace App\Http\Controllers;

use App\GeneralSetting;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$DataSetting = GeneralSetting::all();
		return view('setting.setting', compact('DataSetting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function show(GeneralSetting $generalSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(GeneralSetting $generalSetting)
    {
        //
    }


    public function update(Request $request)
    {
        $input = $request->input();
		if(isset($input['cutOff'])){
            $query = GeneralSetting::where('name','cut-off');

            if($query->count() > 0){
                $query->first()->update(['content' => $input['cutOff']]);
            }else{
                $setting = new GeneralSetting();
                $setting->name = 'cut-off';
                $setting->content = $input['cutOff'];
                $setting->save();
            }
		}

		if(isset($input['generateTagihan'])){
            $query = GeneralSetting::where('name','generate-potongan');

            if($query->count() > 0){
                $query->first()->update(['content' => $input['generateTagihan']]);
            }else{
                $setting = new GeneralSetting();
                $setting->name = 'generate-potongan';
                $setting->content = $input['generateTagihan'];
                $setting->save();
            }
        }

        \Session::flash('message', 'Data seting  berhasil diperbaharui.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(GeneralSetting $generalSetting)
    {
        //
    }
}
