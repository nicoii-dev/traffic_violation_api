<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ViolationList;

class ViolationListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ViolationList::all();
    }

    public function getByCategory($id)
    {
        return DB::table('violation_lists')
        ->where('violation_lists.violation_categories_id', $id)
        ->get();
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
            'violation_categories_id' => 'required',
            'violation_name' => 'required',
            'penalty' => 'required',
        ]);

        ViolationList::create([
            'violation_categories_id' => $request['violation_categories_id'],
            'violation_name' => $request['violation_name'],
            'penalty' => $request['penalty'],
        ]);
        $categories = DB::table('violation_lists')->get();
        return response()->json($categories, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $violation = ViolationList::find($id);
        return response()->json($violation, 200);
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
            'violation_categories_id' => 'required',
            'violation_name' => 'required',
            'penalty' => 'required',
        ]);

        ViolationList::where('id', $id)->update([
            'violation_categories_id' => $request['violation_categories_id'],
            'violation_name' => $request['violation_name'],
            'penalty' => $request['penalty'],
         
        ]);
        $violation = ViolationList::find($id);
        return response()->json($violation, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DB::table("violation_lists")->where('id',$id)->delete()){
            $categories = DB::table('violation_lists')->get();
            return response()->json($categories, 200);
        }else{
            return 500;
        }
    }
}
