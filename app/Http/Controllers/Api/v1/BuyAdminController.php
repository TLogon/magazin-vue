<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buy_product;

class BuyAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->status) {
            $products = Buy_product::where('status_id', $request->status)
                ->join('statuses', 'statuses.id', '=', 'buy_product.status_id')
                ->join('products', 'products.id', '=', 'buy_product.product_id')
                ->leftJoin('product_photos', 'products.avatar', '=', 'product_photos.id')
                ->join('buyers', 'buyers.id', '=', 'products.buyer_id')
                ->join('sections', 'sections.id', '=', 'products.section_id')
                ->join('subsections', 'subsections.id', '=', 'products.subsection_id')
                ->leftJoin('sizes', 'sizes.id', '=', 'buy_product.size_id')
                ->leftJoin('country_codes', 'country_codes.id', '=', 'buy_product.countrycode_id')
                ->leftJoin('countries', 'countries.id', '=', 'products.country_id')
                ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                ->select('buy_product.*', 'buyers.name as byer_name', 'sections.name as section_name', 'subsections.name as subsection_name', 'statuses.name as status_name', 'statuses.id as status_id', 'products.name as product_name', 'products.id as product_id', 'product_photos.photo_link as photo_link', 'products.name as product_name', 'products.price as product_price', 'sizes.name as size_name', 'country_codes.name as country_code_name', 'country_codes.code as country_code_code', 'countries.name as country_name', 'brands.name as brand_name')
                ->orderBy('buy_product.created_at', 'DESC')
                ->get();
        } else {
            $products = Buy_product::where('buy_product.status_id', '!=', 4)
                ->join('statuses', 'statuses.id', '=', 'buy_product.status_id')
                ->join('products', 'products.id', '=', 'buy_product.product_id')
                ->leftJoin('product_photos', 'products.avatar', '=', 'product_photos.id')
                ->join('buyers', 'buyers.id', '=', 'products.buyer_id')
                ->join('sections', 'sections.id', '=', 'products.section_id')
                ->join('subsections', 'subsections.id', '=', 'products.subsection_id')
                ->leftJoin('sizes', 'sizes.id', '=', 'buy_product.size_id')
                ->leftJoin('country_codes', 'country_codes.id', '=', 'buy_product.countrycode_id')
                ->leftJoin('countries', 'countries.id', '=', 'products.country_id')
                ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                ->select('buy_product.*', 'buyers.name as byer_name', 'sections.name as section_name', 'subsections.name as subsection_name', 'statuses.name as status_name', 'statuses.id as status_id', 'products.name as product_name', 'products.id as product_id', 'product_photos.photo_link as photo_link', 'products.name as product_name', 'products.price as product_price', 'sizes.name as size_name', 'country_codes.name as country_code_name', 'country_codes.code as country_code_code', 'countries.name as country_name', 'brands.name as brand_name')
                ->orderBy('buy_product.created_at', 'DESC')
                ->get();
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

    public function Processed($id)
    {
        $buyProduct = Buy_product::findOrFail($id);
        $buyProduct->status_id = 3;
        $buyProduct->save();
        return response()->json("Заказ успешно обработан!");
    }


    public function updateDelete($id)
    {
        $buyProduct = Buy_product::findOrFail($id);
        $buyProduct->status_id = 4;
        $buyProduct->save();
        return response()->json("Заказ успешно удален!");
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
