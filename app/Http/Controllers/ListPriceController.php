<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use Validator;
use Input;
use Session;
use App\ListPrice;
use App\ItemPrices;
use Redirect;

class ListPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
    	$data = new ListPrice;
    	$arrayList = $data->all_list_price();
        return View('list_price.index', compact('arrayList'));
    }

    // update_list_price
    public function update_list_price()
    {
    	$date = date('Y-m-d');
    	$id = Input::get('ID');
    	$list_price = Input::get('list_price');
    	$list_price = str_replace(",", "", $list_price);
    	// Update all List price in-active
        $data = new ItemPrices();
        ItemPrices::where('product_id', $id)->update(
        [
        'is_active' => 1
        ]);

        // Update/Insert List Price
        $arrDataListPrice[] = array( 
                    "product_id" => $id,
                    "list_price" => $list_price,
                    "is_active"  => 0, 
                    "date_price" => $date               
                );
        ItemPrices::insert($arrDataListPrice); 
    	echo number_format($list_price,0);
    }
}
