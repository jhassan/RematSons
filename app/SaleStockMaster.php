<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class SaleStockMaster extends Model
{
    protected $table = "sale_stock_master";

    // Get all Sale Stock
    public function view_sale_stock()
	{
		$arrayStock = DB::table('sale_stock_master')
						->join('parties', 'parties.id', '=', 'sale_stock_master.party_id')
						->join('users', 'users.id', '=', 'sale_stock_master.user_id')
						->select('sale_stock_master.*','parties.name AS party_name','users.name AS user_name')
						->orderBy('id', 'desc')->get();
		return $arrayStock;
	}
}
