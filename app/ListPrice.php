<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class ListPrice extends Model
{
    protected $table = "item_prices";

    // get all list price
    public function all_list_price()
    {
    	$arrayList = DB::table('item_prices')
    					->join('products', 'products.id', '=', 'item_prices.product_id')
    					->select('item_prices.*','products.id AS product_id','products.name AS product_name')
    				  	->where('is_active',0)->orderBy('name', 'asc')
    				  	->paginate(10);
		return $arrayList;
    }
}
