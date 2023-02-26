<?php

namespace App\Http\Controllers;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function searchVehicle(Request $request) {
        $request->validate([
            'plate_number' => 'required',
        ]);
        
        $vehicle = Vehicle::where('plate_number', $request['plate_number'])->with('violator')->first();

        if(strlen($vehicle) > 0) {
            return response()->json($vehicle, 200);
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
            'plate_number' => 'required',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required',
            // 'class' => 'required',
            // 'body_markings' => 'required',
            'registered_owner' => 'required',
            'owner_address' => 'required',
            'vehicle_status' => 'required',
        ]);

        $license = Vehicle::where('plate_number', $request['plate_number'])
        ->where('id', '!=', $id)
        ->first();
        if($license !== null) {
            return response()->json(['message' => 'Plate number is already taken'], 422);
        };
        Vehicle::where('id', $id)->update([
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
        $vehicleInfo = Vehicle::find($id)->with('violator')->first();
        return response()->json($vehicleInfo, 200);
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
