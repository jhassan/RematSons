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
use App\Category;
use App\ItemPrices;
use App\Report;
use Redirect;
use Auth;

class SaleStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all sale stock
        $data = new SaleStockMaster;
        $array_all_sale_stock = $data->view_sale_stock();
        $all_stcok_sale = $array_all_sale_stock['sale_all_stock'];
        $total_quantity = $array_all_sale_stock['sum_all_stock'][0]->TotalQuantity;
        $total_amount = $array_all_sale_stock['sum_all_stock'][0]->TotalAmount;
        return View('sale_stock.index',compact('all_stcok_sale','total_quantity','total_amount'));
        //return View('sale_stock.index', compact('arrayStock'));
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
            'category_id' => 'required',
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
        $category_id = Input::get('category_id');
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
                                "category_id"   => $category_id,
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
                            "sale_stock_master_id" => $last_stock_id,
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
                            "sale_stock_master_id" => $last_stock_id,
                            "product_debit" => Input::get("quantity.$i"),
                            "product_id"    => Input::get("product_id.$i"), 
                            "category_id"   => $category_id,
                            "party_id"      => $party_id,
                            "sale_purchase_date" => $sale_date, 
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

        // Get all Categories
        $category_data = new Category;
        $array_category = $category_data->all_category();      
        return View('sale_stock.add', compact('arrayParties', 'arrayProducts', 'array_category'));
    }

    public function check_total_stcok()
    {
      $id = Input::get('selected_id');
      // Get current stock
      $data = new ProductStock;
      $arrayStock = $data->product_current_stock($id);
      // Get List Price
      $data = new ItemPrices;
      $arrayListPrice = $data->get_itme_price($id);
      $current_stock = number_format($arrayStock[0]->RemainStock);
      $list_price = number_format($arrayListPrice[0]->list_price);
      echo json_encode(array('current_stock' => $current_stock, 'list_price' => $list_price));
      //echo number_format($arrayStock[0]->RemainStock);
      //echo "check_total_stcok".$id;
    }

    // Get check_item_stock_detail
    public function check_item_stock_detail($id)
    {
      $data = new Report;
      $array_all_sale_stock = $data->view_item_detail($id);
      $all_stcok_data = $array_all_sale_stock['view_details'];
      $total_quantity = $array_all_sale_stock['sum_items'][0]->TotalQuantity;
      $credit = $array_all_sale_stock['sum_items'][0]->Credit;
      $debit = $array_all_sale_stock['sum_items'][0]->Debit;
      $ProductName = $array_all_sale_stock['sum_items'][0]->ProductName;
      return View('reports.view_item_details',compact('all_stcok_data','total_quantity','credit', 'debit', 'ProductName'));
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
            $arrayParties = $party_data->all_parties(2);

            // Get all Products
            $product_data = new Product;
            $arrayProducts = $product_data->all_products('s');

            // Get all Categories
            $category_data = new Category;
            $array_category = $category_data->all_category(); 

            // Master Data 
            $master_data = DB::table('sale_stock_master')->where('id', $id)->first();
            // Details Data
            $detail_data = DB::table('sale_stock_detail')
                              ->join('products', 'products.id', '=', 'sale_stock_detail.product_id')  
                              ->select('sale_stock_detail.*','products.name AS product_name')
                              ->where('sale_stock_master_id', $id)->get();
                              //print_r($detail_data); die;
            return View('sale_stock.edit', compact('master_data','arrayParties','arrayProducts', 'detail_data', 'array_category'));
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
