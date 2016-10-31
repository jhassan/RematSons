<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class ProductStock extends Model
{
    protected $table = 'products_stock';

    // Get current stock of a product
    public function product_current_stock($id)
    {
    	$arrayStock = DB::table('products_stock')
                ->select(DB::raw('(SUM(`product_credit`) - SUM(`product_debit`)) AS RemainStock'))
                ->whereRaw('product_id =  "'.$id.'"')
                ->get();
           return $arrayStock;     
    }
}
