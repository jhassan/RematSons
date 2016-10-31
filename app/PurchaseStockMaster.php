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
		$arrayStock['purchasa_all_stock'] = DB::table('purchase_stock_master')
						->join('parties', 'parties.id', '=', 'purchase_stock_master.party_id')
						->join('users', 'users.id', '=', 'purchase_stock_master.user_id')
						->select('purchase_stock_master.*','parties.name AS party_name','users.name AS user_name')
						->orderBy('id', 'desc')->paginate(10);
		$arrayStock['sum_all_stock'] = DB::table('purchase_stock_detail')
                ->select(DB::raw('SUM(quantity) AS TotalQuantity, SUM(total_amount) AS TotalAmount'))
                //->whereRaw('sales.created_at =  "'.$TodayDate.'" AND sales.user_id = '.(int)$user_id.' AND sales.return_id = 0')
                ->get();				
		return $arrayStock;
	}
}
