<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Product extends Model
{
    protected $table = 'products';
    // Get all Parties
    public function all_products($str)
	{
		if($str == "p")
			$arrayProduct = DB::table('products')->orderBy('name', 'asc')->get();
		elseif($str == "s")
		{
			$arrayProduct = DB::table('products')
				->join('products_stock', 'products_stock.product_id', '=', 'products.id')
				->select('products.*')
				->groupBy('products.id')
				->orderBy('name', 'asc')
				->get();
		}
		return $arrayProduct;
	}
}
