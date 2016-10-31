<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class ItemPrices extends Model
{
    protected $table = 'item_prices';

    // Get list price of the product
    public function get_itme_price($id)
    {
    	$arrayProduct = DB::table('item_prices')
    						->select('list_price')	
    						->where('product_id', '=', $id)	
    						->where('is_active', '=', 0)	
    						->get();
    	return $arrayProduct;					
    }
}
