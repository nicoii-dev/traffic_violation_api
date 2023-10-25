<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CommunityService;
use App\Models\CommunityServiceDetails;
use App\Models\Invoice;
use App\Models\Violator;
use App\Models\Citation;

class CommunityServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $communityService = CommunityService::with('citation', 'citation.violator', 'invoice', 'service')->get();
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
            'citation_id' => 'required',
            'invoice_id' => 'required',
            'community_service_details_id' => 'required',
            'status' => 'required',
            'rendered_time' => 'required',
        ]);

        CommunityService::create([
            'citation_id' => $request['citation_id'],
            'invoice_id' => $request['invoice_id'],
            'community_service_details_id' => $request['community_service_details_id'],
            'rendered_time' => $request['rendered_time'],
            'status' => $request['status'],
        ]);

        if($request['status'] === 'settled') {
            $service_details = CommunityServiceDetails::where('id', $request['community_service_details_id'])->first();
            $invoice = Invoice::where('id', $request['invoice_id'])->first();
            // $discount = $service_details->discount / 100;
            // $total = $invoice->total_amount - ($discount * $invoice->total_amount);
            $total = $invoice->total_amount - 500;
            Invoice::where('id', $request['invoice_id'])->update([
                // 'discount' => $service_details->discount,
                'discount' => 500,
                'total_amount' => $total
            ]);
        }

        Invoice::where('id', $request['invoice_id'])->update([
            'status' => 'processed',
        ]);

        $community = CommunityService::with('citation', 'invoice', 'service')->get();
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
        $service = CommunityService::where('id', $id)->with('citation', 'citation.violator', 'invoice', 'service')->get();
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
            'citation_id' => 'required',
            'invoice_id' => 'required',
            'community_service_details_id' => 'required',
            'rendered_time' => 'required',
            'status' => 'required'
        ]);

        $communityService = CommunityService::where('id', $id)->first();
        // if($communityService->citation_id != $request['citation_id']) {
        //     Invoice::where('id', $communityService->invoice_id)->update([
        //         'status' => 'processed',
        //     ]);
        // }

        if($request['status'] === 'settled') {
            $service_details = CommunityServiceDetails::where('id', $request['community_service_details_id'])->first();
            $invoice = Invoice::where('id', $request['invoice_id'])->first();
            // $discount = $service_details->discount / 100;
            // $total = $invoice->total_amount - ($discount * $invoice->total_amount);
            $total = $invoice->total_amount - 500;
            Invoice::where('id', $request['invoice_id'])->update([
                'discount' => 500,
                'total_amount' => $total
            ]);
        }

        CommunityService::where('id', $id)->update([
            'citation_id' => $request['citation_id'],
            'invoice_id' => $request['invoice_id'],
            'community_service_details_id' => $request['community_service_details_id'],
            'rendered_time' => $request['rendered_time'],
            'status' => $request['status'],
        ]);

        $community = CommunityService::where('id', $id)->with('citation', 'invoice', 'service')->get();
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
