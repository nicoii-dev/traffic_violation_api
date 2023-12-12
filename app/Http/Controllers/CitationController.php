<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PaymentRecord;
use App\Models\Violator;
use App\Models\ViolationList;
use App\Models\LicenseInfo;
use App\Models\Vehicle;
use App\Models\CitationInfo;
use App\Models\Invoice;
class CitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $citation = CitationInfo::with('violator', 'enforcer', 'license', 'vehicle', 'invoice')->get();
        // $violationIds = $citation->violations; //getting violation ids only

        foreach($citation as &$row)
        {
            $violationList = ViolationList::whereIn('id', json_decode($row->violations))->get();
            $row['violations'] = $violationList;
        }
        return response()->json($citation, 200);
    }

    public function getAllCitationByEnforcer($id)
    {
        $citation = CitationInfo::where('user_id', $id)->with('violator', 'enforcer', 'license', 'vehicle', 'invoice')->orderBy('created_at','desc')->distinct()->get();
        
        foreach($citation as $row)
        {
            $violationList = ViolationList::whereIn('id', json_decode($row->violations))->get();
            $row->violations = json_encode($violationList);
        }

        return response()->json($citation, 200);
    }

    public function getCitationByEnforcerGroupBy($id)
    {
        $citation = CitationInfo::where('user_id', $id)->with('violator')->groupBy("violator_id")->orderBy('created_at','desc')->distinct()->get();
        
        foreach($citation as $row)
        {
            $violationList = ViolationList::whereIn('id', json_decode($row->violations))->get();
            $row->violations = json_encode($violationList);
        }

        return response()->json($citation, 200);
    }

    public function getCitationByViolator($id)
    {
        $citation = CitationInfo::where('violator_id', $id)->with('violator', 'license', 'vehicle', 'invoice')->orderBy('created_at','desc')->get();
        
        foreach($citation as $row)
        {
            $violationList = ViolationList::whereIn('id', json_decode($row->violations))->get();
            $row->violations = json_encode($violationList);
        }
        return response()->json($citation, 200);
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
            'violatorMunicipality' => 'required',
            'violatorZipcode' => 'required',
            'violatorBarangay' => 'required',
            'violatorStreet' => 'required',
            'nationality' => 'required',
            'phone_number' => 'required',
            'dob' => 'required',
            // 'license_number' => 'required|string|unique:license_infos,license_number',
            'license_type' => 'required',
            'license_status' => 'required',
            // 'plate_number' => 'required|string|unique:vehicles,plate_number',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required',
            // 'class' => 'required',
            // 'body_markings' => 'required',
            'registered_owner' => 'required',
            'owner_address' => 'required',
            'vehicle_status' => 'required',
            
            'violations' => 'required',
            'tct' => 'required',
            'date_of_violation' => 'required',
            'time_of_violation' => 'required',
            'municipality' => 'required',
            'zipcode' => 'required',
            'barangay' => 'required',
            'street' => 'required',
            
            'sub_total' => 'required',
        ]);

        $violator = $this->CheckViolator(
            $request['first_name'],
            $request['middle_name'],
            $request['last_name'],
            $request['gender'],
            $request['violatorMunicipality'],
            $request['violatorZipcode'],
            $request['violatorBarangay'],
            $request['violatorStreet'],
            $request['nationality'],
            $request['phone_number'],
            $request['dob'],
        );

        $vehicle = $this->CheckVehicle(
            $violator->id,
            $request['plate_number'],
            $request['make'],
            $request['model'],
            $request['color'],
            $request['class'],
            $request['body_markings'],
            $request['registered_owner'],
            $request['owner_address'],
            $request['vehicle_status'],
        );
        if($request['license_number'] != null && $request['license_type'] != 'N/A') {
            $old_license = LicenseInfo::where('license_number', $request['license_number'])
            ->where('violator_id', '!=', $violator->id)
            ->first();
            if($old_license != null) {
                return response()->json(['message' => 'License number is already taken'], 422);
            };
        }

        $license = $this->CheckLicense(
            $violator->id,
            $request['license_number'],
            $request['license_type'],
            $request['license_status'],
        );


        $citation = CitationInfo::create([
            'user_id' => Auth::user()->id,
            'violator_id' => $violator->id,
            'license_id' => $license->id,
            'vehicle_id' => $vehicle->id,
            'violations' => $request['violations'],
            'tct' => $request['tct'],
            'date_of_violation' => $request['date_of_violation'],
            'time_of_violation' => $request['time_of_violation'],
            'municipality' => $request['municipality'],
            'zipcode' => $request['zipcode'],
            'barangay' => $request['barangay'],
            'street' => $request['street'],
        ]);

        Invoice::create([
            'citation_id' => $citation->id,
            'date' => $request['date_of_violation'],
            'violations' => $request['violations'],
            'sub_total' => $request['sub_total'],
            'discount' => 0,
            'total_amount' => $request['sub_total'],
            'status' => 'unpaid',
            'expired' => 'no'
        ]);

        // $citation = CitationInfo::with('violator', 'enforcer', 'license', 'vehicle')->get();

        // foreach($citation as $row)
        // {
        //     $violationList = ViolationList::whereIn('id', json_decode($row->violations))->get();
        //     $row['violations'] = $violationList;
        // }

        return response()->json('Successfully Created', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $citation = CitationInfo::find($id);
        if($citation !== null) {
            return response()->json($citation = CitationInfo::where('id', $id)->with('violator', 'enforcer', 'license', 'vehicle')->get(), 200);
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
            'violatorMunicipality' => 'required',
            'violatorZipcode' => 'required',
            'violatorBarangay' => 'required',
            'violatorStreet' => 'required',
            'nationality' => 'required',
            'phone_number' => 'required',
            'dob' => 'required',
            'license_number' => 'required',
            'license_type' => 'required',
            'license_status' => 'required',
            'plate_number' => 'required',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required',
            // 'class' => 'required',
            // 'body_markings' => 'required',
            'registered_owner' => 'required',
            'owner_address' => 'required',
            'vehicle_status' => 'required',
            
            'violations' => 'required',
            'date_of_violation' => 'required',
            'time_of_violation' => 'required',
            'municipality' => 'required',
            'zipcode' => 'required',
            'barangay' => 'required',
            'street' => 'required',
            
            'sub_total' => 'required',
        ]);

        $violator = $this->CheckViolator(
            $request['first_name'],
            $request['middle_name'],
            $request['last_name'],
            $request['gender'],
            $request['violatorMunicipality'],
            $request['violatorZipcode'],
            $request['violatorBarangay'],
            $request['violatorStreet'],
            $request['nationality'],
            $request['phone_number'],
            $request['dob'],
        );

        $citationInfo = CitationInfo::find($id);
        if($citationInfo === null) {
            return response()->json(['message' => 'No record'], 422);
        }
        CitationInfo::where('id', $id)->update([
            'violations' => $request['violations'],
            'violator_id' => $violator->id,
            'date_of_violation' => $request['date_of_violation'],
            'time_of_violation' => $request['time_of_violation'],
            'municipality' => $request['municipality'],
            'zipcode' => $request['zipcode'],
            'barangay' => $request['barangay'],
            'street' => $request['street'],
        ]);

        $invoice = Invoice::where('citation_id', $id)->update([
            'citation_id' => $id,
            'date' => $request['date_of_violation'],
            'violations' => $request['violations'],
            'sub_total' => $request['sub_total'],
            'discount' => 0,
            'total_amount' => $request['sub_total'],
            'status' => 'unpaid',
        ]);

        $license = LicenseInfo::where('license_number', $request['license_number'])
        ->where('id', '!=', $citationInfo->license_id)
        ->first();
        if($license !== null) {
            return response()->json(['message' => 'License number is already taken'], 422);
        };
        LicenseInfo::where('id', $citationInfo->license_id)->update([
            'violator_id' => $violator->id,
            'license_number' => $request['license_number'],
            'license_type' => $request['license_type'],
            'license_status' => $request['license_status'],
        ]);

        $license = Vehicle::where('plate_number', $request['plate_number'])
        ->where('id', '!=', $citationInfo->vehicle_id)
        ->first();
        if($license !== null) {
            return response()->json(['message' => 'Plate number is already taken'], 422);
        };
        Vehicle::where('id', $citationInfo->vehicle_id)->update([
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

        $citation = CitationInfo::where('id', $id)->with('violator', 'enforcer', 'license', 'vehicle', 'invoice')->get();

        foreach($citation as &$row)
        {
            $violationList = ViolationList::whereIn('id', json_decode($row->violations))->get();
            $row->violations = $violationList;
        }

        return response()->json(['message' => 'Updated Successfully', "data" => $citation], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DB::table("citation_infos")->where('id',$id)->delete()){
            $violator = DB::table('citation_infos')->get();
            return response()->json($violator, 200);
        }else{
            return response()->json(['message' => 'Unprocessable'], 422);
        }
    }

    private function CheckViolator(
        $first_name,
        $middle_name,
        $last_name,
        $gender,
        $violatorMunicipality,
        $violatorZipcode,
        $violatorBarangay,
        $violatorStreet,
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
                    'municipality' => $violatorMunicipality,
                    'zipcode' => $violatorZipcode,
                    'barangay' => $violatorBarangay,
                    'street' => $violatorStreet,
                    'nationality' => $nationality,
                    'phone_number' => $phone_number,
                    'dob' => $dob,
                ]);
                return $violator;
            }

    }

    private function CheckVehicle(
        $violator_id,
        $plate_number,
        $make,
        $model,
        $color,
        $class,
        $body_markings,
        $registered_owner,
        $owner_address,
        $vehicle_status
        )
    {
        $vehicleData = Vehicle::where('violator_id', $violator_id)
            ->where('plate_number', $plate_number)
            ->where('make', $make)
            ->where('model', $model)
            ->where('color', $color)
            ->first();

            if($vehicleData != null) {
                return $vehicleData;
            } else {
                $newVehicle = Vehicle::create([
                    'violator_id' => $violator_id,
                    'plate_number' => $plate_number,
                    'make' => $make,
                    'model' => $model,
                    'color' => $color,
                    'class' => $class,
                    'body_markings' => $body_markings,
                    'registered_owner' => $registered_owner,
                    'owner_address' => $owner_address,
                    'vehicle_status' => $vehicle_status,
                ]);
                return $newVehicle ;
            }

    }

    private function CheckLicense(
        $violator_id,
        $license_number,
        $license_type,
        $license_status,
        )
    {
        $licenseData = LicenseInfo::where('violator_id', $violator_id)
            ->where('license_number', $license_number)
            ->first();

            if($licenseData != null) {
                return $licenseData;
            } else {
                $newLicense = LicenseInfo::create([
                    'violator_id' => $violator_id,
                    'license_number' => $license_number,
                    'license_type' => $license_type,
                    'license_status' => $license_status,
                ]);
                return $newLicense;
            }

    }
}
