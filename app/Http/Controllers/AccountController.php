<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use Validator;
use Input;
use Session;
use App\COA;
use App\ItemPrices;
use Redirect;

class AccountController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    // view list transections
    public function list_transections()
    {
    	$data = new COA;
    	$arrayList = $data->get_all_transection();
        return View('accounts.list_transections', compact('arrayList'));
    }
}
