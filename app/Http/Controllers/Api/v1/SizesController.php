<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sizes;

class SizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$sizes = Sizes::where('section_id', $request->section_id)
        ->select('id', 'name',)
        ->orderBy('name')->get();
        return response()->json([
            'data' => $sizes,
        ]);*/

        //echo json_encode($request->section_id);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*$sizes = Sizes::find($id);
        if (!$sizes) {
            return response()->json([
                "status" => false,
                "message" => "Размер не найден"
            ])->setStatusCode(404);
        }
        return response()->json([
            'status' => true,
            'data' => $sizes,
        ]);*/
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

    public function GetSizes(Request $request)
    {
        $sizes = Sizes::where('section_id', $request->section_id)
            ->select('id', 'name',)
            ->orderBy('id')->get();
        return response()->json([
            'data' => $sizes,
        ]);

        //echo json_encode($request->section_id);
    }
}
