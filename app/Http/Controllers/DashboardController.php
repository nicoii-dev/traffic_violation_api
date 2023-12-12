<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CitationInfo;
use App\Models\User;
use App\Models\Invoice;
use App\Models\PaymentRecord;

class DashboardController extends Controller
{
    public function index()
    {
        
        $citation = CitationInfo::all()->count();
        $citationByMonth = CitationInfo::query()
        ->select(DB::raw("count(*) as total, DATE_FORMAT(date_of_violation, '%m') as month"))
        ->groupByRaw('MONTHNAME(date_of_violation)')
        ->orderBy('date_of_violation', 'ASC')
        ->get();
        $users = User::all();
        $usersCount = User::all()->count();
        $invoice = Invoice::all()->count();
        $payments = PaymentRecord::all()->sum('total_paid');

        return response()->json(["citation" => $citation, "citationByMonth" => $citationByMonth, "users" => $users, "usersCount" => $usersCount, "invoice" => $invoice, "payments" => $payments], 200);
    }
}
