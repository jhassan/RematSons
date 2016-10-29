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
use App\SaleStockMaster;
use App\SaleStockDetail;
use App\ProductStock;
use App\Product;
use Redirect;
use Auth;

class SaleStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all sale stock
        $stock_data = new SaleStockMaster;
        $arrayStock = $stock_data->view_sale_stock();
        return View('sale_stock.index', compact('arrayStock'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //print_r(Input::all()); die;
        //DB::transaction(function () {
        $rules = array(
            'party_id'  => 'required',
            'bilty_no'  => 'required',
            'adda_address'  => 'required',
            'quantity'  => 'required',
            'discount_id'  => 'required',
            'product_id' => 'required'
        );
        $date = date("Y-m-d H:i:s");
        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        //$data = new PurchaseStockMaster();
        
        // Insert in purchase master table
        $party_id = Input::get('party_id');
        $bilty_no = Input::get('bilty_no');
        $total_quantity = Input::get('total_quantity');
        $grand_total = Input::get('grand_total');
        $bilty_no = Input::get('bilty_no');
        $total_item = Input::get('total_item');
        $user_id = Auth::user()->id;
        $adda_address = Input::get('adda_address');
        $sale_date = date("Y-m-d",strtotime(Input::get('sale_date')));
        $arrayInsert = array('party_id' => $party_id, 
                                "created_at"    => $date,
                                "sale_date"     => $sale_date,
                                "bilty_no"      => $bilty_no,
                                "total_quantity"=> $this->RemoveComma($total_quantity),
                                "grand_total"   => $this->RemoveComma($grand_total),
                                "total_item"    => $this->RemoveComma($total_item),
                                "adda_address"  => $adda_address,
                                "user_id"       => $user_id);
        //if($party_id != 0 && !empty($party_id))
        $last_stock_id = SaleStockMaster::insertGetId($arrayInsert);

        // Insert in sale detail table
        if($last_stock_id != 0 && $last_stock_id != "")
        {
            $product_id = Input::get('product_id');
            for($i=0; $i<count($product_id); $i++)
            {
                $arrData[] = array( 
                            "sale_stock_master_id"      => $last_stock_id,
                            "quantity"      => Input::get("quantity.$i"),
                            "product_id"    => Input::get("product_id.$i"), 
                            "discount"      => $this->RemoveComma(Input::get("discount_id.$i")),
                            "net_price"     => $this->RemoveComma(Input::get("net_price.$i")), 
                            "list_price"    => $this->RemoveComma(Input::get("list_price.$i")),
                            "total_amount"  => $this->RemoveComma(Input::get("total_amount.$i")),
                            "created_at"    => $date               
                        );
                // For Stock Handling
                $arrDataStock[] = array( 
                            "product_debit" => Input::get("quantity.$i"),
                            "product_id"    => Input::get("product_id.$i"), 
                            "created_at"    => $date               
                        );
            }
            ProductStock::insert($arrDataStock);
            SaleStockDetail::insert($arrData);
        }
        Session::flash('purchase_message', "Sale added successfully!");
        return Redirect::back();
       // }); // End transections
    }

    public function add()
    {
        // Get all parties
        $party_data = new Party;
        $arrayParties = $party_data->all_parties(2);

        // Get all Products
        $product_data = new Product;
        $arrayProducts = $product_data->all_products('s');        
        return View('sale_stock.add', compact('arrayParties', 'arrayProducts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            // Get all parties
            $party_data = new Party;
            $arrayParties = $party_data->all_parties(1);

            // Get all Products
            $product_data = new Product;
            $arrayProducts = $product_data->all_products('s');

            // Master Data 
            $master_data = DB::table('sale_stock_master')->where('id', $id)->first();
            // Details Data
            $detail_data = DB::table('sale_stock_detail')
                              ->join('products', 'products.id', '=', 'sale_stock_detail.product_id')  
                              ->select('sale_stock_detail.*','products.name AS product_name')
                              ->where('sale_stock_master_id', $id)->get();
                              //print_r($detail_data); die;
            return View('sale_stock.edit', compact('master_data','arrayParties','arrayProducts', 'detail_data'));
        }
        catch (TestimonialNotFoundException $e) {
            return Redirect::route('sale_stock.edit')->with('error', 'Error Message');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id = null)
    {
        // Remove existing records
        $this->destroy($id, "update");
        // Insert new records
        $this->create($id);
        Session::flash('sale_stock_edit', "Sale Stock update successfully!");
        return redirect('sale_stock');
    }

    // Remove commas in a numeric number
    public function RemoveComma($value)
    {
        return str_replace(",", "", $value);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
