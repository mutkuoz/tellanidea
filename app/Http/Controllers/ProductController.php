<?php

namespace App\Http\Controllers;

use DB;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        //
    }
    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show(Product $product){
        //
    }
    public function edit(Product $product){
        //
    }

    public function update(Request $request, Product $product){
        //
    }

    public function destroy(Product $product){
        //
    }

    public function getProductList(Request $request) {
        if ($request->ajax()){
            $page = $request->input('page');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $products= Product::where('name', 'LIKE',  '%' . $request->input('term'). '%')
                ->whereRaw('penetrationActionEnabled>0 or volumeActionEnabled>0 or spreadActionEnabled>0')
                ->orderBy('name')
                ->skip($offset)
                ->take($resultCount)
                ->get(['id','penetrationActionEnabled','volumeActionEnabled','spreadActionEnabled',DB::raw('name as text')]);

            $count = Product::count();
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $results = array(
                "results" => $products,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }
}
