<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Violator;
use App\Models\LicenseInfo;
use App\Models\Vehicle;

class ViolatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Violator::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'nationality' => 'required',
            'phone_number' => 'required',
            'dob' => 'required',
            'license_number' => 'required',
            'license_type' => 'required',
            'license_status' => 'required',
            'plate_number' => 'required',
            // 'make' => 'required',
            // 'model' => 'required',
            // 'color' => 'required',
            // 'class' => 'required',
            // 'body_markings' => 'required',
            'registered_owner' => 'required',
            'owner_address' => 'required',
            'vehicle_status' => 'required',
        ]);

        $violator = Violator::create([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'gender' => $request['gender'],
            'address' => $request['address'],
            'nationality' => $request['nationality'],
            'phone_number' => $request['phone_number'],
            'dob' => $request['dob'],
        ]);

        LicenseInfo::create([
            'violator_id' => $violator->id,
            'license_number' => $request['license_number'],
            'license_type' => $request['license_type'],
            'license_status' => $request['license_status'],
        ]);

        Vehicle::create([
            'violator_id' => $violator->id,
            'plate_number' => $request['plate_number'],
            'make' => $request['make'],
            'model' => $request['model'],
            'color' => $request['color'],
            'class' => $request['class'],
            'body_markings' => $request['body_markings'],
            'registered_owner' => $request['registered_owner'],
            'owner_address' => $request['owner_address'],
            'vehicle_status' => $request['vehicle_status'],
        ]);

        $violator = Violator::with('license', 'vehicle')->get();
        return response()->json($violator, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $violator = Violator::find($id);
        if($violator !== null) {
            return response()->json($violator::with('license', 'vehicle')->get(), 200);
        };
        return [];
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
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'nationality' => 'required',
            'phone_number' => 'required',
            'dob' => 'required',
        ]);
        
        Violator::where('id', $id)->update([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'gender' => $request['gender'],
            'address' => $request['address'],
            'nationality' => $request['nationality'],
            'phone_number' => $request['phone_number'],
            'dob' => $request['dob'],
        ]);
        $violator = Violator::find($id);
        return response()->json($violator, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DB::table("violators")->where('id',$id)->delete()){
            $violator = DB::table('violators')->get();
            return response()->json($violator, 200);
        }else{
            return 500;
        }
    }
}
