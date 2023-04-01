<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CommunityService;

class CommunityServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $communityService = CommunityService::with('violator', 'service')->get();
        return response()->json($communityService, 200);
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
            'violator_id' => 'required',
            'community_service_details_id' => 'required',
            'status' => 'required',
            'rendered_time' => 'required',
        ]);

        CommunityService::create([
            'violator_id' => $request['violator_id'],
            'community_service_details_id' => $request['community_service_details_id'],
            'rendered_time' => $request['rendered_time'],
            'status' => 1,
        ]);

        $community = CommunityService::with('violator', 'service')->get();
        return response()->json(["message" => "Successfully Created", "data" => $community], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = CommunityService::find($id);
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
            'violator_id' => 'required',
            'community_service_details_id' => 'required',
            'rendered_time' => 'required',
            'status' => 'required'
        ]);

        CommunityService::where('id', $id)->update([
            'violator_id' => $request['violator_id'],
            'community_service_details_id' => $request['community_service_details_id'],
            'rendered_time' => $request['rendered_time'],
            'status' => $request['status'],
        ]);

        $community = CommunityService::where('id', $id)->with('violator', 'service')->get();
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
        //
    }
}
