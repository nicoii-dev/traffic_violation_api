<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PaymentRecord;
use App\Models\Invoice;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PaymentRecord::with('invoice', 'invoice.citation.violator')->get();
        return response()->json($data, 200);
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
            'invoice_id' => 'required',
            'payment_date' => 'required',
            'payment_method' => 'required',
            'total_paid' => 'required',
            'remarks' => 'required',
        ]);

        PaymentRecord::create([
            'invoice_id' => $request['invoice_id'],
            'payment_date' => $request['payment_date'],
            'payment_method' => $request['payment_method'],
            'total_paid' => $request['total_paid'],
            'remarks' => $request['remarks'],
        ]);

        Invoice::where('id', $request['invoice_id'])->update([
            'status' => 'paid',
        ]);

        $payment = PaymentRecord::with('invoice', 'invoice.citation.violator')->get();
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
            'invoice_id' => 'required',
            'payment_date' => 'required',
            'payment_method' => 'required',
            'total_paid' => 'required',
            'remarks' => 'required',
        ]);

        PaymentRecord::where('id', $id)->update([
            'invoice_id' => $request['invoice_id'],
            'payment_date' => $request['payment_date'],
            'payment_method' => $request['payment_method'],
            'total_paid' => $request['total_paid'],
            'remarks' => $request['remarks'],
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
