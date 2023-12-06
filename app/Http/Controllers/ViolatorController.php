<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
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
        
        $violator = Violator::with('license')->with('vehicle')->get();
        return response()->json($violator, 200);
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

    public function search(Request $request)
    {
        $search = $request['search'];
        $violation = Violator::where('last_name', 'like', "%{$search}%")->with('license')->with('vehicle')->get();
        return response()->json($violation, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
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
            return response()->json($violator::where('id', $id)->with('license', 'vehicle')->get(), 200);
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
