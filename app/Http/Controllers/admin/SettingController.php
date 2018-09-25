<?php

namespace App\Http\Controllers\admin;

use App\Setting;
use File;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['active'] = "active";
        $data['open'] = 'open';
        $data['setting'] ='active';
        $data['editData'] = Setting::first();
        return view('admin.setting.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax()){
            $setting =Setting::find($request->id);
            $this->validate($request, $setting->rules($request->id));

            if($request->hasFile('logo')){
                if($setting->logo){
                    $oldfile = $setting->logo;
                    if (File::exists("companylogo/" . $oldfile)) {
                        File::delete("companylogo/" . $oldfile);
                    }
                }

                $file = $request->logo;
                $destinationPath = public_path() . DIRECTORY_SEPARATOR.'companylogo';
                $extension = $file->getClientOriginalExtension();
                $filename = random_int(10000,99999) . "." . $extension;
                $file->move($destinationPath, $filename);
                $setting->logo = $filename;
                Image::make($destinationPath.DIRECTORY_SEPARATOR.$filename)->save($destinationPath.DIRECTORY_SEPARATOR.$filename);
            }

            $setting->company_name = $request->name;
            $setting->email = $request->email;
            $setting->address = $request->address;
            $setting->phone = $request->phone;
            $setting->save();



            return ['status' => 'success','message' => 'Settings successfully Saved',
                'image' => asset('companylogo/'.$setting->logo)];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
