<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            return $sale = Sale::max('datetime');
         }else{
             return view('sale');
         }
    }

    public function indexSales(){   

        $f = Sale::max('datetime');
        $f = (string) $f;
        $value = substr($f, 0, -9);  // devuelve date

        $allSales = DB::table('sales')
        ->join('foods', 'foods.id', '=', 'sales.food_id')
        ->select('sales.*', 'foods.name as food_name')
        ->where('datetime','like','%'.$value.'%')
        ->orderBy('sales.datetime','ASC')
        ->get();
        return $allSales;
    }
    public function searchSales($fec){
        $sales = Sale::where('datetime','like','%'.$fec.'%')->get();
        return $sales;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sale = new Sale();
        $sale->datetime = $request->get('datetime');
        $sale->food_id = $request->get('food_id');
        $sale->amount = $request->get('amount');
        $sale->save();
        return $sale;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::find($id);
        return $sale;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $sale = Sale::find($id);
        $sale->datetime = $request->datetime;
        $sale->food_id = $request->food_id;
        $sale->amount = $request->amount;
        $sale->save();
        return $sale;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale = Sale::find($id);
        $sale->delete();
    }
}
