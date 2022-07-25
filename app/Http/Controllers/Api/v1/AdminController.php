<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function GetBuyers()
    {
        echo json_encode(DB::table('buyers')->orderBy('name')->get());
    }
    public function GetSections($id)
    {
        echo json_encode(DB::table('sections')->where('buyer_id', $id)->orderBy('name')->get());
    }
    public function GetSubSections($id)
    {
        echo json_encode(DB::table('subsections')->where('section_id', $id)->orderBy('name')->get());
    }

}
