<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Buy_product;

class Buy_productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'countrycode_id' => 'required',
                'phone' => 'required',
                'product_id' => 'required',
                'quantity' => 'required',
                //'size_id' => 'required',
                'total' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json('errorFormat');
        }
        $BuyProduct = new Buy_product();
        $BuyProduct->status_id = 1;
        $BuyProduct->name = $request->name;
        $BuyProduct->countrycode_id = $request->countrycode_id;
        $BuyProduct->phone = $request->phone;
        $BuyProduct->address = $request->address;
        $BuyProduct->product_id = $request->product_id;
        $BuyProduct->quantity = $request->quantity;
        if($request->size_id)
        {
            $BuyProduct->size_id = $request->size_id;
        }
        $BuyProduct->total = $request->total;
        $rezult = $BuyProduct->save();
        return response()->json([
            'status' => true,
            'BuyProduct' => $BuyProduct,
        ]);
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
