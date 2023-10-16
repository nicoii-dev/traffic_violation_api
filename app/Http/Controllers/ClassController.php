<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\VehicleClass;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return VehicleClass::all();
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
            'class' => 'required|unique:vehicle_classes,class'
        ]);

        VehicleClass::create([
            'class' => $request['class'],
        ]);
        $categories = DB::table('vehicle_classes')->get();
        return response()->json($categories, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $class = VehicleClass::find($id);
        return response()->json($class, 200);
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
            'class' => 'required'
        ]);

        $vehicleClass = VehicleClass::where('class', $request['class'])
        ->where('id', '!=', $id)
        ->first();
        if($vehicleClass !== null) {
            return response()->json(["message" => "This class is already been taken."], 422);
        };

        VehicleClass::where('id', $id)->update([
            'class' => $request['class'],
        ]);
        $class = VehicleClass::find($id);
        return response()->json($class, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DB::table("vehicle_classes")->where('id',$id)->delete()){
            $class = DB::table('vehicle_classes')->get();
            return response()->json($class, 200);
        }else{
            return 500;
        }
    }
}
