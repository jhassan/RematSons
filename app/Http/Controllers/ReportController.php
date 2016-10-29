<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use View;
use DB;
use Validator;
use Input;
use Session;
use App\Party;
use Redirect;
use App\Report;

class ReportController extends Controller
{
    // View Purchase Report
    public function view_purchase_stock()
    {
        $data = new Report;
	 	$array_all_purchase_stock = $data->view_all_purchase_stock();
        $all_stcok_purchase = $array_all_purchase_stock['all_stock'];
        $total_quantity = $array_all_purchase_stock['sum_all_stock'][0]->TotalQuantity;
        $total_amount = $array_all_purchase_stock['sum_all_stock'][0]->TotalAmount;
	 	return View('reports.view_purchase_stock',compact('all_stcok_purchase','total_quantity','total_amount'));
    }

    // View Sale Report
    public function view_sale_stock()
    {
        $data = new Report;
	 	$array_all_sale_stock = $data->view_all_sale_stock();
        $all_stcok_sale = $array_all_sale_stock['all_stock'];
        $total_quantity = $array_all_sale_stock['sum_all_stock'][0]->TotalQuantity;
        $total_amount = $array_all_sale_stock['sum_all_stock'][0]->TotalAmount;
	 	return View('reports.view_sale_stock',compact('all_stcok_sale','total_quantity','total_amount'));
    }

    public function view_today_stock()
    {
    	$data = new Report;
	 	$array_today_stock = $data->view_today_stock();
	 	return View('reports.view_today_stock',compact('array_today_stock'));
    }
}
