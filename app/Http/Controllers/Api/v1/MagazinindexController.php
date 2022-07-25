<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buyers;
use App\Models\Sections;
use App\Models\Subsections;
use App\Models\Countries;
use App\Models\Brands;
use App\Models\Products;
use App\Models\Product_photos;
use App\Models\Product_sizes;
use Illuminate\Support\Facades\DB;

class MagazinindexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = '';
        if ($request->search) {
            $search = $request->search;
        }
        $country_list = "";
        if ($request->country) {
            $country_list = explode(",", $request->country);
        }
        $brand_list = "";
        if ($request->brand) {
            $brand_list = explode(",", $request->brand);
        }
        $size_list = "";
        if ($request->size) {
            $size_list = explode(",", $request->size);
        }
        $products = Products::where('products.active', true)
            ->when($search != '', function ($products) use ($search) {
                $products->where('products.name', 'like', '%' . $search . '%');
            })
            ->join('buyers', 'buyers.id', '=', 'products.buyer_id')
            ->join('sections', 'sections.id', '=', 'products.section_id')
            ->join('subsections', 'subsections.id', '=', 'products.subsection_id')
            ->leftJoin('product_photos', 'products.avatar', '=', 'product_photos.id')
            ->when($size_list != '', function ($products) use ($size_list) {
                $products->leftJoin('product_sizes', 'products.id', '=', 'product_sizes.product_id')
                    ->whereIn('size_id', $size_list);
            })
            ->select('products.*', 'buyers.name as byer_name', 'sections.name as section_name', 'subsections.name as subsection_name', 'product_photos.photo_link')
            ->groupBy('products.id', 'byer_name', 'section_name', 'subsection_name', 'photo_link')
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
        if ($request->priceFrom) {
            $products = $products->where('price', '>=', $request->priceFrom);
        }
        if ($request->priceTo) {
            $products = $products->where('price', '<=', $request->priceTo);
        }
        if ($country_list != "") {
            $products = $products->whereIn('country_id', $country_list);
        }
        if ($brand_list != "") {
            $products = $products->whereIn('brand_id', $brand_list);
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
        $meta_buyer = '';
        if ($request->buyer) {
            $buyer = Buyers::find($request->buyer);
            $meta_buyer = $buyer->meta;
        }
        $meta_section = '';
        if ($request->section) {
            $section = Sections::find($request->section);
            $meta_section = $section->name;
        }
        $meta_subsection = '';
        if ($request->subsection) {
            $subsection = Subsections::find($request->subsection);
            $meta_subsection = $subsection->name;
        }
        return response()->json([
            'status' => true,
            'current_page' => $page,
            'last_page' => $last_page,
            'total' => $total,
            'data' => $products,
            'meta_buyer' => $meta_buyer,
            'meta_section' => $meta_section,
            'meta_subsection' => $meta_subsection,
        ]);
    }

    public function MagazinMenu()
    {
        $buyers = Buyers::orderBy('name')->get();
        foreach ($buyers as $buyer) {
            $buyer->sections = Sections::where('buyer_id', $buyer->id)->orderBy('name')->get();
            foreach ($buyer->sections as $buye) {
                $buye->subsections = Subsections::where('section_id', $buye->id)->orderBy('name')->get();
            }
        }
        return response()->json([
            'status' => true,
            'menu' => $buyers,
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


    public function SelectCountries()
    {
        echo json_encode(Countries::where('active', true)->orderBy('name')->get());
    }
    public function SelectBrands()
    {
        echo json_encode(Brands::where('active', true)->orderBy('name')->get());
    }


    public function LoadFavourites(Request $request)
    {
        if ($request->id) {
            $arrayID = explode(",", $request->id);
            $products = Products::whereIn('products.id', $arrayID)
                ->where('products.active', true)
                ->leftJoin('product_photos', 'products.avatar', '=', 'product_photos.id')
                ->select('products.id', 'products.name', 'products.price', 'product_photos.photo_link')
                ->orderBy('products.name')->get();
            return response()->json([
                'status' => true,
                'data' => $products,
            ]);
        }
        return response()->json([
            'status' => null,
        ]);
    }


    public function LoadCart(Request $request)
    {
        if ($request->id) {
            $arrayID = explode(",", $request->id);
            $products = Products::whereIn('products.id', $arrayID)
                ->where('products.active', true)
                ->leftJoin('product_photos', 'products.avatar', '=', 'product_photos.id')
                ->select('products.*', 'product_photos.photo_link')
                ->orderBy('products.name')->get();
            return response()->json([
                'status' => true,
                'data' => $products,
            ]);
        }
        return response()->json([
            'status' => null,
        ]);
    }


    public function LoadProduct($id)
    {
        $products = Products::where('products.id', $id)
            ->leftJoin('buyers', 'buyers.id', '=', 'products.buyer_id')
            ->leftJoin('sections', 'sections.id', '=', 'products.section_id')
            ->leftJoin('subsections', 'subsections.id', '=', 'products.subsection_id')
            ->leftJoin('countries', 'countries.id', '=', 'products.country_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->leftJoin('product_photos', 'products.avatar', '=', 'product_photos.id')
            ->select('products.*', 'buyers.name as byer_name', 'sections.name as section_name', 'subsections.name as subsection_name', 'countries.name as country_name', 'brands.name as brand_name')
            ->get();
        $sizes = Product_sizes::where('product_id', $id)
            ->leftJoin('sizes', 'sizes.id', '=', 'product_sizes.size_id')
            ->select('product_sizes.*', 'sizes.name as size_name')
            ->orderBy('id')->get();
        $photos = Product_photos::where('product_id', $id)
            ->select('id', 'photo_link')
            ->orderBy('id')->get();
        if (!$products) {
            return response()->json([
                "status" => false,
                "message" => "Продукт не найден"
            ])->setStatusCode(404);
        }
        return response()->json([
            'status' => true,
            'products' => $products,
            'sizes' => $sizes,
            'photos' => $photos,
        ]);
    }
}
