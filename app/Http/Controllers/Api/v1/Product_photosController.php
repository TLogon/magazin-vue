<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product_photos;
use App\Models\Products;

class Product_photosController extends Controller
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
        if ($request->file('photo')) {
            $i = 1;
            $time = time();
            $product_avatar = Products::find($request->id);

            foreach ($request->file('photo') as $file) {
                $fileName = $request->id . "_" . $i . "_" . $time . "." . $file->getClientOriginalExtension();
                $file->move(public_path('files'), $fileName);
                
                $Product_photos = new Product_photos();
                $Product_photos->product_id = $request->id;
                $Product_photos->photo_link = $fileName;
                $Product_photos->save();

                if ($product_avatar->avatar == null && $i == 1) {
                    $product_avatar->avatar = $Product_photos->id;
                    $product_avatar->save();
                }

                $i++;
            }
        }

        return response()->json([
            'avatar' => $product_avatar->avatar
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
        $product_photos = Product_photos::where('product_id', $id)->get();
        return response()->json([
            'status' => true,
            'data' => $product_photos,
        ]);
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
        



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $photo = Product_photos::find($id);
        $photo->delete();
        unlink(public_path('files/' . $photo->photo_link));
        if ($request->avatar == true) {
            $product_avatar = Products::find($request->idProduct);
            $product_photos_avatar_new = Product_photos::where('product_id', $request->idProduct)->first();
            if (empty($product_photos_avatar_new->id)) {
                $product_avatar->avatar = null;
            } else {
                $product_avatar->avatar = $product_photos_avatar_new->id;
            }
            $product_avatar->save();
        }
        return response()->json([
            'avatar' => $product_avatar->avatar
        ]);
    }
}
