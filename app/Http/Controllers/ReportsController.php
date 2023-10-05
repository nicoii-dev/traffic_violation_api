<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentRecord;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function incomeReport(Request $request)
    {
        $request->validate([
            'mode' => 'required',
            'yearStart' => 'nullable',
            'yearEnd' => 'nullable',
            'year' => 'nullable',
            'monthStart' => 'nullable',
            'monthEnd' => 'nullable',
            'dateStart' => 'nullable',
            'dateEnd' => 'nullable',
        ]);
       if($request['mode'] == 'yearly') {
        $paymentRecordsYearly = PaymentRecord::with('invoice')->get();
        $breakdown = [];
            for($i = $request['yearStart']; $i <= $request['yearEnd']; $i++){
                $overall_total = 0;
                    foreach($paymentRecordsYearly as $result) {
                        if(date("Y", strtotime($result['payment_date'])) == $i){
                            $overall_total = $overall_total + $result['total_paid'];
                            array_push($breakdown, $result);
                        }	
                    }	
                    $yearly_report[] = array("year"=>"$i", "value"=>"$overall_total");
                }
            return response()->json(["breakdown" => $breakdown, "data" => $yearly_report], 200);

        } else if($request['mode'] == 'quarterly') {
            $paymentRecordsQuarterly = PaymentRecord::where(\DB::raw('YEAR(payment_date)'), '=', $request['year'] )->with('invoice')->with('invoice')->get();
            $quarterly_report = array();
            $breakdown = [];
            for($i = 1; $i <= 4; $i++){	
                if($i == 1){
                    $overall_total = 0;										
                    for($a=1; $a <=3; $a++){
                        foreach($paymentRecordsQuarterly as $result) {
                            if(date("m", strtotime($result['payment_date'])) == $a){
                                $overall_total = $overall_total + (int)$result['total_paid'];
                                array_push($breakdown, $result);
                            }
                        }
        
                    }
                    //echo "₱"; echo$overall_total; echo '<br>';	
                    $quarterly_report[] = array("quarter"=>"1", "value"=>$overall_total);
                }else if($i == 2){
                    $overall_total = 0;
                    for($b=4; $b <=6; $b++){
                        foreach($paymentRecordsQuarterly as $result) {
                            if(date("m", strtotime($result['payment_date'])) == $b){
                                $overall_total = $overall_total + (int)$result['total_paid'];
                                array_push($breakdown, $result);
                            }
                        }
                            
                    }
                    //echo "₱"; echo$overall_total; echo '<br>';	
                    $quarterly_report[] = array("quarter"=>"2", "value"=>$overall_total);								
                }else if($i == 3){
                    $overall_total = 0;
                    for($c=7; $c <=9; $c++){
                        foreach($paymentRecordsQuarterly as $result) {
                            if(date("m", strtotime($result['payment_date'])) == $c){
                                $overall_total = $overall_total + (int)$result['total_paid'];
                                array_push($breakdown, $result);
                            }
                        }
                            
                    }
                    //echo "₱"; echo$overall_total; echo '<br>';
                    $quarterly_report[] = array("quarter"=>"3", "value"=>$overall_total);											
                }else if($i == 4){
                    $overall_total = 0;
                    for($d=10; $d <=12; $d++){
                        foreach($paymentRecordsQuarterly as $result) {
                            if(date("m", strtotime($result['payment_date'])) == $d){
                                $overall_total = $overall_total + (int)$result['total_paid'];
                                array_push($breakdown, $result);
                            }
                        }
                            
                    }
                    //echo "₱"; echo$overall_total; echo '<br>';
                    $quarterly_report[] = array("quarter"=>"4", "value"=>$overall_total);											
                }				
            }	
            return response()->json(["breakdown" => $breakdown, "data" => $quarterly_report], 200);
            
       } else if($request['mode'] == 'monthly') {
        $paymentRecordsMonthly = PaymentRecord::where(\DB::raw('YEAR(payment_date)'), '=', $request['year'] )->with('invoice')->get();
        $breakdown = [];
        for($i = $request['monthStart']; $i <= $request['monthEnd']; $i++){
            $overall_total = 0;
                foreach($paymentRecordsMonthly as $result) {
                    if(date("m", strtotime($result['payment_date'])) == $i){
                        array_push($breakdown, $result);
                        $overall_total = $overall_total + (int)$result['total_paid'];
                    }	
                }	              
                $month_report[] = array("month"=> $i, "value" => $overall_total);
            }
            return response()->json(["breakdown" => $breakdown, "data" => $month_report], 200);
       }

       return response()->json(["message" => "invalid request"], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function incomeReportByUser($id, Request $request)
    {
        $request->validate([
            'mode' => 'required',
            'yearStart' => 'nullable',
            'yearEnd' => 'nullable',
            'year' => 'nullable',
            'monthStart' => 'nullable',
            'monthEnd' => 'nullable',
            'dateStart' => 'nullable',
            'dateEnd' => 'nullable',
        ]);
       if($request['mode'] == 'yearly') {
        $paymentRecordsYearly = PaymentRecord::where('user_id', $id)->with('invoice')->get();
        $breakdown = [];
            for($i = $request['yearStart']; $i <= $request['yearEnd']; $i++){
                $overall_total = 0;
                    foreach($paymentRecordsYearly as $result) {
                        if(date("Y", strtotime($result['payment_date'])) == $i){
                            $overall_total = $overall_total + $result['total_paid'];
                            array_push($breakdown, $result);
                        }	
                    }	
                    $yearly_report[] = array("year"=>"$i", "value"=>"$overall_total");
                }
            return response()->json(["breakdown" => $breakdown, "data" => $yearly_report], 200);

        } else if($request['mode'] == 'quarterly') {
            $paymentRecordsQuarterly = PaymentRecord::where(\DB::raw('YEAR(payment_date)'), '=', $request['year'] )->where('user_id', $id)->with('invoice')->get();
            $quarterly_report = array();
            $breakdown = [];
            for($i = 1; $i <= 4; $i++){	
                if($i == 1){
                    $overall_total = 0;										
                    for($a=1; $a <=3; $a++){
                        foreach($paymentRecordsQuarterly as $result) {
                            if(date("m", strtotime($result['payment_date'])) == $a){
                                $overall_total = $overall_total + (int)$result['total_paid'];
                                array_push($breakdown, $result);
                            }
                        }
        
                    }
                    //echo "₱"; echo$overall_total; echo '<br>';	
                    $quarterly_report[] = array("quarter"=>"1", "value"=>$overall_total);
                }else if($i == 2){
                    $overall_total = 0;
                    for($b=4; $b <=6; $b++){
                        foreach($paymentRecordsQuarterly as $result) {
                            if(date("m", strtotime($result['payment_date'])) == $b){
                                $overall_total = $overall_total + (int)$result['total_paid'];
                                array_push($breakdown, $result);
                            }
                        }
                            
                    }
                    //echo "₱"; echo$overall_total; echo '<br>';	
                    $quarterly_report[] = array("quarter"=>"2", "value"=>$overall_total);								
                }else if($i == 3){
                    $overall_total = 0;
                    for($c=7; $c <=9; $c++){
                        foreach($paymentRecordsQuarterly as $result) {
                            if(date("m", strtotime($result['payment_date'])) == $c){
                                $overall_total = $overall_total + (int)$result['total_paid'];
                                array_push($breakdown, $result);
                            }
                        }
                            
                    }
                    //echo "₱"; echo$overall_total; echo '<br>';
                    $quarterly_report[] = array("quarter"=>"3", "value"=>$overall_total);											
                }else if($i == 4){
                    $overall_total = 0;
                    for($d=10; $d <=12; $d++){
                        foreach($paymentRecordsQuarterly as $result) {
                            if(date("m", strtotime($result['payment_date'])) == $d){
                                $overall_total = $overall_total + (int)$result['total_paid'];
                                array_push($breakdown, $result);
                            }
                        }
                            
                    }
                    //echo "₱"; echo$overall_total; echo '<br>';
                    $quarterly_report[] = array("quarter"=>"4", "value"=>$overall_total);											
                }				
            }	
            return response()->json(["breakdown" => $breakdown, "data" => $quarterly_report], 200);
            
       } else if($request['mode'] == 'monthly') {
        $paymentRecordsMonthly = PaymentRecord::where(\DB::raw('YEAR(payment_date)'), '=', $request['year'] )->where('user_id', $id)->with('invoice')->get();
        $breakdown = [];
        for($i = $request['monthStart']; $i <= $request['monthEnd']; $i++){
            $overall_total = 0;
                foreach($paymentRecordsMonthly as $result) {
                    if(date("m", strtotime($result['payment_date'])) == $i){
                        array_push($breakdown, $result);
                        $overall_total = $overall_total + (int)$result['total_paid'];
                    }	
                }	              
                $month_report[] = array("month"=> $i, "value" => $overall_total);
            }
            return response()->json(["breakdown" => $breakdown, "data" => $month_report], 200);
       }

       return response()->json(["message" => "invalid request"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
