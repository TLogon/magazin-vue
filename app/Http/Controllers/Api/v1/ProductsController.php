<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Product_photos;
use App\Models\Product_sizes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use File;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $search = '';
        if ($request->name) {
            $search = $request->name;
        }
        $products = Products::where('products.active', true)->where('products.name', 'like', '%' . $search . '%')
            ->join('buyers', 'buyers.id', '=', 'products.buyer_id')
            ->join('sections', 'sections.id', '=', 'products.section_id')
            ->join('subsections', 'subsections.id', '=', 'products.subsection_id')
            ->leftJoin('product_photos', 'products.avatar', '=', 'product_photos.id')
            ->select('products.*', 'buyers.name as byer_name', 'sections.name as section_name', 'subsections.name as subsection_name', 'product_photos.photo_link')
            ->orderBy('products.created_at', 'DESC')->get();
        if ($request->buyer) {
            $products = $products->where('buyer_id', $request->buyer);
        }
        if ($request->section) {
            $products = $products->where('section_id', $request->section);
        }
        if ($request->subsection) {
            $products = $products->where('subsection_id', $request->subsection);
        }
        $limit = 10;
        $total = count($products);
        $page = 1;
        $last_page = ceil($total / $limit);
        if ($request->page && $request->page > 0) {
            $page = $request->page;
        }
        if ($request->page && $request->page > $last_page) {
            $page = $last_page;
        }
        $products = $products->forPage($page, $limit);
        $from = ($page * $limit) - $limit + 1;
        $i = 0;
        foreach ($products as $product) {
            $product->productNumber =  $from + $i;
            $i++;
        }
        return response()->json([
            'status' => true,
            'current_page' => $page,
            'last_page' => $last_page,
            'total' => $total,
            'data' => $products,
        ]);
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
                'name' => 'required|min:5|max:100',
                'price' => 'required|min:0|max:999999999999|numeric',
                'buyer_id' => 'required',
                'section_id' => 'required',
                'subsection_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json('errorFormat');
        }

        $newProduct = new Products();
        $newProduct->buyer_id = $request->buyer_id;
        $newProduct->section_id = $request->section_id;
        $newProduct->subsection_id = $request->subsection_id;
        $newProduct->name = $request->name;
        $newProduct->price = $request->price;
        $newProduct->country_id = $request->country_id;
        $newProduct->brand_id = $request->brand_id;

        $newProduct->save();
        $id = $newProduct->id;

        if ($request->sizes) {
            foreach ($request->sizes as $size) {
                $product_sizes = new Product_sizes();
                $product_sizes->product_id = $id;
                $product_sizes->size_id = $size[0];
                $product_sizes->save();
            }
        }

        if ($request->file('photo')) {
            $i = 1;
            $time = time();
            foreach ($request->file('photo') as $file) {
                $fileName = $id . "_" . $i . "_" . $time . "." . $file->getClientOriginalExtension();
                $file->move(public_path('files'), $fileName);
                $Product_photos = new Product_photos();
                $Product_photos->product_id = $id;
                $Product_photos->photo_link = $fileName;
                $Product_photos->save();
                if ($i == 1) {
                    $idphoto = $Product_photos->id;
                    DB::table('products')->where('id', $id)->update(['avatar' => $idphoto]);
                }
                $i++;
            }
        }
        return $newProduct;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Products::find($id);
        return response()->json([
            'status' => true,
            'data' => $product
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
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:5|max:100',
                'price' => 'required|min:0|max:999999999999|numeric',
                'buyer_id' => 'required',
                'section_id' => 'required',
                'subsection_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json('errorFormat');
        }
        $editProduct = Products::findOrFail($request->product_id);
        $editProduct->buyer_id = $request->buyer_id;
        $editProduct->section_id = $request->section_id;
        $editProduct->subsection_id = $request->subsection_id;
        $editProduct->name = $request->name;
        $editProduct->price = $request->price;
        $editProduct->country_id = $request->country_id;
        $editProduct->brand_id = $request->brand_id;
        $rezult = $editProduct->save();
        $sizes_in_base = Product_sizes::where('product_id', $request->product_id)->select('id', 'size_id')->get();
        if ($request->sizes) {
            foreach ($request->sizes as $size) {
                $bool_add = false;
                foreach ($sizes_in_base as $size_in_base) {
                    if ($size[0] == $size_in_base->size_id) {
                        $bool_add = true;
                    }
                }
                if ($bool_add == false) {
                    $product_sizes = new Product_sizes();
                    $product_sizes->product_id = $request->product_id;
                    $product_sizes->size_id = $size[0];
                    $product_sizes->save();
                }
            }
        }
        if (count($sizes_in_base) > 0) {
            foreach ($sizes_in_base as $size_in_base) {
                $bool_del = false;
                foreach ($request->sizes as $size) {
                    if ($size_in_base->size_id == $size[0]) {
                        $bool_del = true;
                    }
                }
                if ($bool_del == false) {
                    $product = Product_sizes::find($size_in_base->id);
                    $product->delete();
                }
            }
        }
        if ($rezult) {
            return ["rezult" => 'Cохранение успешно завершено'];
        } else {
            return ["rezult" => 'Ошибка при сохранении'];
        }
    }

    public function avatar(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        $product->avatar = $request->idPhoto;
        $rezult = $product->save();
        if ($rezult) {
            return response()->json([
                'status' => true,
                'avatar' => $product->avatar
            ]);
        } else {
            return response()->json([
                'status' => false,
                'messages' => 'Ошибка при изменении аватара'
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::find($id);
        $product->delete();
        return response()->json("Продукт успешно удален!");
    }
}
