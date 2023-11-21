<?php

namespace App\Http\Controllers;
use App\Models\ViolationList;
use App\Models\CitationInfo;
use Illuminate\Http\Request;
use App\Models\PaymentRecord;

class ReportsViolationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function violationReport(Request $request)
    {
        $request->validate([
            'violation_id' => 'required',
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
                $violation = ViolationList::where('id', $request['violation_id'])->first();
                    foreach($paymentRecordsYearly as $result) {
                        if(date("Y", strtotime($result['payment_date'])) == $i){
                            // check if the violation exist in invoice->violations
                            if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                // then total the times of how many times violation appears
                                $overall_total = $overall_total + $violation->penalty;
                            };
                            array_push($breakdown, $result);
                        }	
                    }	
                    $yearly_report[] = array("year"=>"$i", "value"=>"$overall_total");
                }
            return response()->json(["breakdown" => $breakdown, "data" => $yearly_report, "violation" => $violation], 200);

        } else if($request['mode'] == 'quarterly') {
            $paymentRecordsQuarterly = PaymentRecord::where(\DB::raw('YEAR(payment_date)'), '=', $request['year'] )->with('invoice')->with('invoice')->get();
            $quarterly_report = array();
            $breakdown = [];
            for($i = 1; $i <= 4; $i++){	
                if($i == 1){
                    $overall_total = 0;		
                    $violation = ViolationList::where('id', $request['violation_id'])->first();								
                    for($a=1; $a <=3; $a++){
                        foreach($paymentRecordsQuarterly as $result) {
                            if(date("m", strtotime($result['payment_date'])) == $a){
                                // check if the violation exist in invoice->violations
                                if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + $violation->penalty;
                                };
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
                                // check if the violation exist in invoice->violations
                                if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + $violation->penalty;
                                };
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
                                // check if the violation exist in invoice->violations
                                if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + $violation->penalty;
                                };
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
                                // check if the violation exist in invoice->violations
                                if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + $violation->penalty;
                                };
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
                        // check if the violation exist in invoice->violations
                        if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                            // then total the times of how many times violation appears
                            $overall_total = $overall_total + $violation->penalty;
                        };
                        array_push($breakdown, $result);
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
    public function violationReportByUser(Request $request, $id)
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
                            // check if the violation exist in invoice->violations
                            if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                // then total the times of how many times violation appears
                                $overall_total = $overall_total + $violation->penalty;
                            };
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
                                // check if the violation exist in invoice->violations
                                if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + $violation->penalty;
                                };
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
                                // check if the violation exist in invoice->violations
                                if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + $violation->penalty;
                                };
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
                                // check if the violation exist in invoice->violations
                                if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + $violation->penalty;
                                };
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
                                // check if the violation exist in invoice->violations
                                if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + $violation->penalty;
                                };
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
                        // check if the violation exist in invoice->violations
                        if(in_array($request['violation_id'], json_decode($result["invoice"]->violations))){
                            // then total the times of how many times violation appears
                            $overall_total = $overall_total + $violation->penalty;
                        };
                        array_push($breakdown, $result);
                    }	
                }	              
                $month_report[] = array("month"=> $i, "value" => $overall_total);
            }
            return response()->json(["breakdown" => $breakdown, "data" => $month_report], 200);
       }

       return response()->json(["message" => "invalid request"], 200);
    }

    public function showMostCommittedViolation(Request $request)
    {
        $request->validate([
            'mode' => 'required',
            'year' => 'nullable',
            'yearStart' => 'nullable',
            'yearEnd' => 'nullable',
            'year' => 'nullable',
            'monthStart' => 'nullable',
            'monthEnd' => 'nullable',
            'dateStart' => 'nullable',
            'dateEnd' => 'nullable',
        ]);

        if($request['mode'] == 'yearly') {
            $citations = CitationInfo::get();
            $violations = ViolationList::get();
            $per_year_top_violation = array();
                for($i = $request['yearStart']; $i <= $request['yearEnd']; $i++){
                    $topViolation = [];
                    foreach($violations as $violation){
                        $overall_total = 0;
                        foreach($citations as $result) {
                            if(date("Y", strtotime($result['date_of_violation'])) == $i){
                                    // check if the violation exist in invoice->violations
                                if(in_array($violation["id"], json_decode($result["violations"]))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + 1;
                                };
                            }
                        }
                        if($overall_total > 0) {
                            if(count($topViolation) > 0) {
                                if(array_values($topViolation)[0]["total"] < $overall_total) {
                                    unset($topViolation);
                                    array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                } else if (array_values($topViolation)[0]["total"] == $overall_total) {
                                    array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                }
                            } else {
                                array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                            }
                        }
                    }	
                    $per_year_top_violation[] = ['name' => $i, 'violation' => $topViolation];	
                }
                return response()->json(["data" => $per_year_top_violation], 200);
        } else if($request['mode'] == 'quarterly') {
            $quarterly_report = array();
            $citations = CitationInfo::where(\DB::raw('YEAR(date_of_violation)'), '=', $request['year'] )->get();
            $violations = ViolationList::get();
            for($i = 1; $i <= 4; $i++){	
                $per_quarter_top_violation = array();
                if($i == 1){
                    for($a=1; $a <=3; $a++){
                        $topViolation = [];	
                        foreach($violations as $violation){
                            $overall_total = 0;	
                            foreach($citations as $result) {
                                if(date("m", strtotime($result['date_of_violation'])) == $a){
                                    // check if the violation exist in invoice->violations
                                    if(in_array($violation["id"], json_decode($result["violations"]))){
                                        // then total the times of how many times violation appears
                                        $overall_total = $overall_total + 1;
                                    };
                                }
                            }
                            if($overall_total > 0) {
                                if(count($topViolation) > 0) {
                                    if(array_values($topViolation)[0]["total"] < $overall_total) {
                                        unset($topViolation);
                                        array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                    } else if (array_values($topViolation)[0]["total"] == $overall_total) {
                                        array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                    }
                                } else {
                                    array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                }
                            }
                        }
                        if($overall_total > 0) {
                            $per_quarter_top_violation = $topViolation;
                        }
                    }
                    //echo "₱"; echo$overall_total; echo '<br>';	
                    $quarterly_report[] = array("name"=>"Quarter 1", "violation" => $per_quarter_top_violation);
                }
                else if($i == 2){
                    $per_quarter_top_violation = array();
                    for($a=4; $a <=6; $a++){
                        $topViolation = [];		
                        foreach($violations as $violation){
                            $overall_total = 0;
                            foreach($citations as $result) {
                                if(date("m", strtotime($result['date_of_violation'])) == $a){
                                    // check if the violation exist in invoice->violations
                                    if(in_array($violation["id"], json_decode($result["violations"]))){
                                        // then total the times of how many times violation appears
                                        $overall_total = $overall_total + 1;
                                    };
                                }
                            }
                            if($overall_total > 0) {
                                if(count($topViolation) > 0) {
                                    if(array_values($topViolation)[0]["total"] < $overall_total) {
                                        unset($topViolation);
                                        array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                    } else if (array_values($topViolation)[0]["total"] == $overall_total) {
                                        array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                    }
                                } else {
                                    array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                }
                            }
                        }
                        if($overall_total > 0) {
                            $per_quarter_top_violation = $topViolation;
                        }
                    }
                    $quarterly_report[] = array("name"=>"Quarter 2", "violation" => $per_quarter_top_violation);							
                }else if($i == 3){
                    $per_quarter_top_violation = array();
                    for($a=7; $a <=9; $a++){
                        $topViolation = [];	
                        foreach($violations as $violation){
                            $overall_total = 0;	
                            foreach($citations as $result) {
                                if(date("m", strtotime($result['date_of_violation'])) == $a){
                                    // check if the violation exist in invoice->violations
                                    if(in_array($violation["id"], json_decode($result["violations"]))){
                                        // then total the times of how many times violation appears
                                        $overall_total = $overall_total + 1;
                                    };
                                }
                            }
                            if($overall_total > 0) {
                                if(count($topViolation) > 0) {
                                    if(array_values($topViolation)[0]["total"] < $overall_total) {
                                        unset($topViolation);
                                        array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                    } else if (array_values($topViolation)[0]["total"] == $overall_total) {
                                        array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                    }
                                } else {
                                    array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                }
                            }
                        }
                        if($overall_total > 0) {
                            $per_quarter_top_violation = $topViolation;
                        }
                    }
                    $quarterly_report[] = array("name"=>"Quarter 3", "violation" => $per_quarter_top_violation);									
                }else if($i == 4){
                    $per_quarter_top_violation = array();
                    for($a=10; $a <=12; $a++){
                        $topViolation = [];	
                        foreach($violations as $violation){
                            $overall_total = 0;
                            foreach($citations as $result) {
                                if(date("m", strtotime($result['date_of_violation'])) == $a){
                                    // check if the violation exist in invoice->violations
                                    if(in_array($violation["id"], json_decode($result["violations"]))){
                                        // then total the times of how many times violation appears
                                        $overall_total = $overall_total + 1;
                                    };
                                }
                            }
                            if($overall_total > 0) {
                                if(count($topViolation) > 0) {
                                    if(array_values($topViolation)[0]["total"] < $overall_total) {
                                        unset($topViolation);
                                        array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                    } else if (array_values($topViolation)[0]["total"] == $overall_total) {
                                        array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                    }
                                } else {
                                    array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                }
                            }
                        }
                        if($overall_total > 0) {
                            $per_quarter_top_violation = $topViolation;
                        }
                    }
                    $quarterly_report[] = array("name"=>"Quarter 4", "violation" => $per_quarter_top_violation);												
                }				
            }	
                return response()->json(["data" => $quarterly_report], 200); 
        } else if($request['mode'] == 'monthly') { 
            $citations = CitationInfo::where(\DB::raw('YEAR(date_of_violation)'), '=', $request['year'] )->get();
            $violations = ViolationList::get();
            $per_year_top_violation = array();
                for($i = $request['monthStart']; $i <= $request['monthEnd']; $i++){
                    $topViolation = [];
                    foreach($violations as $violation){
                        $overall_total = 0;
                        foreach($citations as $result) {
                            if(date("m", strtotime($result['date_of_violation'])) == $i){
                                    // check if the violation exist in invoice->violations
                                if(in_array($violation["id"], json_decode($result["violations"]))){
                                    // then total the times of how many times violation appears
                                    $overall_total = $overall_total + 1;
                                };
                            }
                        }
                        if($overall_total > 0) {
                            if(count($topViolation) > 0) {
                                if(array_values($topViolation)[0]["total"] < $overall_total) {
                                    unset($topViolation);
                                    array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                } else if (array_values($topViolation)[0]["total"] == $overall_total) {
                                    array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                                }
                            } else {
                                array_push($topViolation, ['name' => $violation["violation_name"], 'total' => $overall_total]);
                            }
                        }
                    }	
                    $per_year_top_violation[] = array("name" => date('F', mktime(0, 0, 0, $i, 10)), "violation" => $topViolation);	
                }
                return response()->json(["data" => $per_year_top_violation], 200);
        }
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
