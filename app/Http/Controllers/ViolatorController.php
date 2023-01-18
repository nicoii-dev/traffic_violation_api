<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Violator;
use App\Models\ViolationList;
use App\Models\LicenseInfo;
use App\Models\Vehicle;
use App\Models\CitationInfo;
use App\Models\Invoice;
use App\Models\InvoiceDetails;

class ViolatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $citation = Violator::with('license', 'vehicle', 'citation')->get(); //gettings all data
        $violationIds = $citation[0]->citation->violations; //getting violation ids only
        $violationList = ViolationList::whereIn('id', json_decode($violationIds))->get();
        return ['citation'=>$citation, 'violations'=>$violationList];
    }

    public function searchViolator(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
        ]);

        $violatorData = Violator::where('first_name', $request['first_name'])
            ->where('last_name', $request['last_name'])
            ->where('dob', $request['dob'])
            ->first();
            if($violatorData != null) {
                return $violatorData;
            } else {
                return [];
            }

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
            
            'user_id' => 'required',
            'violations' => 'required',
            'date_of_violation' => 'required',
            'time_of_violation' => 'required',
            'municipality' => 'required',
            'zipcode' => 'required',
            'barangay' => 'required',
            'street' => 'required',
        ]);

        $violator = $this->CheckViolator(
            $request['first_name'],
            $request['middle_name'],
            $request['last_name'],
            $request['gender'],
            $request['address'],
            $request['nationality'],
            $request['phone_number'],
            $request['dob'],
        );

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

        CitationInfo::create([
            'violator_id' => $violator->id,
            'user_id' => $request['user_id'],
            'violations' => $request['violations'],
            'date_of_violation' => $request['date_of_violation'],
            'time_of_violation' => $request['time_of_violation'],
            'municipality' => $request['municipality'],
            'zipcode' => $request['zipcode'],
            'barangay' => $request['barangay'],
            'street' => $request['street'],
        ]);

        $invoice = Invoice::create([
            'violator_id' => $violator->id,
            'date' => $request['date_of_violation'],
            'total_amount' => $request['total_amount'],
            'status' => 1,
        ]);
        foreach(json_decode($request['violations']) as $violations) {
            InvoiceDetails::create([
                'invoice_id' => $invoice->id,
                'date' => $request['date'],
                'violation_id' => $violations,
            ]);
        }
       

        $response = Violator::with('license', 'vehicle', 'citation')->get();
        return response()->json($response, 200);
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
            // return response()->json($violator::with('license', 'vehicle', 'citation')->get(), 200);
            return response()->json($violator::where('id', $id)->with('license', 'vehicle', 'citation')->get(), 200);
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

    private function CheckViolator(
        $first_name,
        $middle_name,
        $last_name,
        $gender,
        $address,
        $nationality,
        $phone_number,
        $dob)
    {
        $violatorData = Violator::where('first_name', $first_name)
            ->where('last_name', $last_name)
            ->where('dob', $dob)
            ->first();

            if($violatorData != null) {
                return $violatorData;
            } else {
                $violator = Violator::create([
                    'first_name' => $first_name,
                    'middle_name' => $middle_name,
                    'last_name' => $last_name,
                    'gender' => $gender,
                    'address' => $address,
                    'nationality' => $nationality,
                    'phone_number' => $phone_number,
                    'dob' => $dob,
                ]);
                return $violator;
            }

    }
}