<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CommunityServiceDetails;

class CommunityServiceDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CommunityServiceDetails::all();
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
            'service_name' => 'required|unique:community_service_details,service_name',
            'discount' => 'required',
            'time_to_render' => 'required'
        ]);

        CommunityServiceDetails::create([
            'service_name' => $request['service_name'],
            'discount' => $request['discount'],
            'time_to_render' => $request['time_to_render'],
        ]);

        $community = DB::table('community_service_details')->get();
        return response()->json(["message" => "Created Successfully", "data" => $community], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = CommunityServiceDetails::find($id);
        return response()->json($service, 200);
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
            'service_name' => 'required',
            'discount' => 'required',
            'time_to_render' => 'required'
        ]);

        $communityService = CommunityServiceDetails::where('service_name', $request['service_name'])
        ->where('id', '!=', $id)
        ->first();
        if($communityService !== null) {
            return response()->json(["message" => 'The service name has already been taken.'], 422);
        };

        CommunityServiceDetails::where('id', $id)->update([
            'service_name' => $request['service_name'],
            'discount' => $request['discount'],
            'time_to_render' => $request['time_to_render'],
        ]);

        $community = DB::table('community_service_details')->get();
        return response()->json(["message" => "Updated Successfully", "data" => $community], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DB::table("community_service_details")->where('id',$id)->delete()){
            $categories = DB::table('community_service_details')->get();
            return response()->json($categories, 200);
        }else{
            return 500;
        }
    }
}
