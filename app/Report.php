<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Report extends Model
{
	// View Purchase 
	public function view_all_purchase_stock()
	{
		// View Purchase Stock
    	$arrayVouchers['all_stock'] = DB::table('purchase_stock_master')
				->join('purchase_stock_detail', 'purchase_stock_detail.stock_master_id', '=', 'purchase_stock_master.id')
				->join('parties', 'parties.id', '=', 'purchase_stock_master.party_id')
				->join('products', 'products.id', '=', 'purchase_stock_detail.product_id')
                ->select('purchase_stock_master.*','purchase_stock_detail.*','parties.name AS party_name','products.name AS product_name')
                ->orderBy('purchase_date', 'desc')
                ->paginate(10);
        $arrayVouchers['sum_all_stock'] = DB::table('purchase_stock_detail')
                ->select(DB::raw('SUM(quantity) AS TotalQuantity, SUM(total_amount) AS TotalAmount'))
                //->whereRaw('sales.created_at =  "'.$TodayDate.'" AND sales.user_id = '.(int)$user_id.' AND sales.return_id = 0')
                ->get();
		return $arrayVouchers;
	}
	// View Sale 
	public function view_all_sale_stock()
	{
		// View Purchase Stock
    	$arrayVouchers['all_stock'] = DB::table('sale_stock_master')
				->join('sale_stock_detail', 'sale_stock_detail.sale_stock_master_id', '=', 'sale_stock_master.id')
				->join('parties', 'parties.id', '=', 'sale_stock_master.party_id')
				->join('products', 'products.id', '=', 'sale_stock_detail.product_id')
                ->select('sale_stock_master.*','sale_stock_detail.*','parties.name AS party_name','products.name AS product_name')
                ->orderBy('sale_date', 'desc')
                ->paginate(10);
        $arrayVouchers['sum_all_stock'] = DB::table('sale_stock_detail')
                ->select(DB::raw('SUM(quantity) AS TotalQuantity, SUM(total_amount) AS TotalAmount'))
                //->whereRaw('sales.created_at =  "'.$TodayDate.'" AND sales.user_id = '.(int)$user_id.' AND sales.return_id = 0')
                ->get();        
		return $arrayVouchers;
	}

	// View today stock
    public function view_today_stock()
    {
    	$arrayDetail['today_stock'] = DB::table('products_stock')
						->join('products', 'products.id', '=', 'products_stock.product_id')
						->select(DB::raw('(SUM(product_credit) - SUM(product_debit)) AS TotalStock, products.name AS product_name, products.id AS product_id')) // , products.name AS product_name, products.id AS product_id
						->groupBy('product_id')
						->orderBy('product_name', 'asc')
						->paginate(10);
		$arrayDetail['sum_all_stock'] = DB::table('products_stock')
                ->select(DB::raw('(SUM(product_credit) - SUM(product_debit)) AS TotalQuantity'))
                //->whereRaw('sales.created_at =  "'.$TodayDate.'" AND sales.user_id = '.(int)$user_id.' AND sales.return_id = 0')
                ->get();				
		return $arrayDetail;
    }

    // View item details
    public function view_item_detail($id)
    {
    	$arrayDetail['view_details'] = DB::table('products_stock')
						->join('parties', 'parties.id', '=', 'products_stock.party_id')
						->select(DB::raw('`product_id`, `product_credit`, `product_debit`, `sale_purchase_date`, `name`')) 
						->whereRaw('products_stock.product_id =  "'.$id.'"')
						->paginate(10);
		$arrayDetail['sum_items'] = DB::table('products_stock')
                ->join('products', 'products.id', '=', 'products_stock.product_id')
                ->select(DB::raw('(SUM(product_credit) - SUM(product_debit)) AS TotalQuantity, SUM(product_credit) AS Credit, SUM(product_debit) AS Debit, products.name AS ProductName  '))
                ->whereRaw('products_stock.product_id =  "'.$id.'"')
                ->get();				
		return $arrayDetail;
    }
	
}
