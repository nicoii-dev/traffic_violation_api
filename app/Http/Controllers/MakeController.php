<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\VehicleMake;

class MakeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return VehicleMake::all();
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
            'make' => 'required|unique:vehicle_makes,make'
        ]);

        VehicleMake::create([
            'make' => $request['make'],
        ]);
        $makes = DB::table('vehicle_makes')->get();
        return response()->json($makes, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $make = VehicleMake::find($id);
        return response()->json($make, 200);
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
            'make' => 'required'
        ]);

        $vehicleMake = VehicleMake::where('make', $request['make'])
        ->where('id', '!=', $id)
        ->first();
        if($vehicleMake !== null) {
            return response()->json(["message" => "This make is already been taken."], 422);
        };

        VehicleMake::where('id', $id)->update([
            'make' => $request['make'],
        ]);
        $make = VehicleMake::find($id);
        return response()->json($make, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DB::table("vehicle_makes")->where('id',$id)->delete()){
            $make = DB::table('vehicle_makes')->get();
            return response()->json($make, 200);
        }else{
            return 500;
        }
    }
}
