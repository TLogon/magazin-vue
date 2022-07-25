<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brands;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brands::orderBy('name')->get();
        echo json_encode($brands);
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
                "country_id" => ["required"],
                "name" => ["required"]
            ]
        );
        if ($validator->fails()) {
            echo json_encode([
                "status" => false,
                "errors" => 'Ошибка валидации'
            ]);
        }
        $newBrand = new Brands();
        $newBrand->country_id = $request->country_id;
        $newBrand->name = $request->name;
        $newBrand->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo json_encode(Brands::where('country_id', $id)->where('active', true)->orderBy('name')->get());
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
        $validator = Validator::make(
            $request->all(),
            [
                "name" => ["required"],
                "country_id" => ["required"]
            ]
        );
        if ($validator->fails()) {
            echo json_encode([
                "status" => false,
                "errors" => 'Ошибка валидации'
            ]);
        }
        $editBrand = Brands::findOrFail($id);
        $editBrand->name = $request->name;
        $editBrand->country_id = $request->country_id;
        $rezult = $editBrand->save();
        if ($rezult) {
            return ["rezult" => 'Cохранение успешно завершено'];
        } else {
            return ["rezult" => 'Ошибка при сохранении'];
        }
    }

    public function updateDelete($id)
    {
        $countries = Brands::findOrFail($id);
        $countries->active  = false;
        $countries->save();
        return response()->json("Бренд успешно удален!");
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
