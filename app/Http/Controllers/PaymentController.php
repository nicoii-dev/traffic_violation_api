<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PaymentRecord;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return PaymentRecord::all();
        return PaymentRecord::with('citation')->get();
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
            'driver_id' => 'required',
            'citation_id' => 'required',
            'discount' => 'required',
            'total_amount' => 'required',
            'total_paid' => 'required',
        ]);

        PaymentRecord::create([
            'driver_id' => $request['driver_id'],
            'citation_id' => $request['citation_id'],
            'discount' => $request['discount'],
            'total_amount' => $request['total_amount'],
            'total_paid' => $request['total_paid'],
        ]);
        $payment = DB::table('payment_records')->get();
        return response()->json($payment, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = PaymentRecord::find($id);
        return response()->json($payment, 200);
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
            'driver_id' => 'required',
            'citation_id' => 'required',
            'discount' => 'required',
            'total_amount' => 'required',
            'total_paid' => 'required',
        ]);

        PaymentRecord::where('id', $id)->update([
            'driver_id' => $request['driver_id'],
            'citation_id' => $request['citation_id'],
            'discount' => $request['discount'],
            'total_amount' => $request['total_amount'],
            'total_paid' => $request['total_paid'],
        ]);
        $payment = PaymentRecord::find($id);
        return response()->json($payment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DB::table("payment_records")->where('id',$id)->delete()){
            $categories = DB::table('payment_records')->get();
            return response()->json($categories, 200);
        }else{
            return 500;
        }
    }
}
