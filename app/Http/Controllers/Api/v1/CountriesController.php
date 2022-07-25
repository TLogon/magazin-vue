<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Countries;
use Illuminate\Support\Facades\Validator;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo json_encode(Countries::where('active', true)->orderBy('name')->get());
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
                "name" => ["required"]
            ]
        );
        if ($validator->fails()) {
            echo json_encode([
                "status" => false,
                "errors" => 'Ошибка валидации'
            ]);
        }
        $newCountry = new Countries();
        $newCountry->name = $request->name;
        $newCountry->save();
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
        $validator = Validator::make(
            $request->all(),
            [
                "name" => ["required"]
            ]
        );
        if ($validator->fails()) {
            echo json_encode([
                "status" => false,
                "errors" => 'Ошибка валидации'
            ]);
        }
        $editCountry = Countries::findOrFail($id);
        $editCountry->name = $request->name;
        $rezult = $editCountry->save();
        if ($rezult) {
            return ["rezult" => 'Cохранение успешно завершено'];
        } else {
            return ["rezult" => 'Ошибка при сохранении'];
        }
    }

    public function updateDelete($id)
    {
        $countries = Countries::findOrFail($id);
        $countries->active  = false;
        $countries->save();
        return response()->json("Страна успешно удалена!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Countries::find($id);
        $product->delete();
        return response()->json("Страна успешно удалена!");
    }
}
