<?php

namespace App\Http\Controllers;
use App\Models\LicenseInfo;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function searchLicense(Request $request) {
        $request->validate([
            'license_number' => 'required',
        ]);
        
        $license = LicenseInfo::where('license_number', $request['license_number'])->with('violator')->first();

        if(strlen($license) > 0) {
            return response()->json($license, 200);
        } else {
            return response()->json(['message' => 'No record'], 422);
        }
    }

    public function index()
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $request->validate([
            'license_number' => 'required',
            'license_type' => 'required',
            'license_status' => 'required',
        ]);

        $license = LicenseInfo::where('license_number', $request['license_number'])
        ->where('id', '!=', $id)
        ->first();
        if($license !== null) {
            return response()->json(['message' => 'License number is already taken'], 422);
        };
        LicenseInfo::where('id', $id)->update([
            'license_number' => $request['license_number'],
            'license_type' => $request['license_type'],
            'license_status' => $request['license_status'],
        ]);
        $licenseInfo = LicenseInfo::find($id)->with('violator')->first();
        return response()->json($licenseInfo, 200);
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
