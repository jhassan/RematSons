<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class PurchaseStockMaster extends Model
{
    protected $table = "purchase_stock_master";

    // Get all Purchase Stock
    public function view_purchase_stock()
	{
		$arrayStock = DB::table('purchase_stock_master')
						->join('parties', 'parties.id', '=', 'purchase_stock_master.party_id')
						->join('users', 'users.id', '=', 'purchase_stock_master.user_id')
						->select('purchase_stock_master.*','parties.name AS party_name','users.name AS user_name')
						->orderBy('id', 'desc')->get();
		return $arrayStock;
	}
}
