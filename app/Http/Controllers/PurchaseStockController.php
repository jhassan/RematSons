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
use App\PurchaseStockMaster;
use App\PurchaseStockDetail;
use App\ProductStock;
use App\Product;
use App\Category;
use App\COA;
use App\PurchaseVoucherMaster;
use App\PurchaseVoucherDetail;
use Redirect;
use Auth;
use App\ItemPrices;

class PurchaseStockController extends Controller
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
        // Get all purchase stock
        $data = new PurchaseStockMaster;
        $array_all_purchase_stock = $data->view_purchase_stock();
        $all_stcok_purchase = $array_all_purchase_stock['purchasa_all_stock'];
        $total_quantity = $array_all_purchase_stock['sum_all_stock'][0]->TotalQuantity;
        $total_amount = $array_all_purchase_stock['sum_all_stock'][0]->TotalAmount;
        //$arrayStock = $stock_data->view_purchase_stock();
        return View('purchase_stock.index',compact('all_stcok_purchase','total_quantity','total_amount'));
        //return View('purchase_stock.index', compact('arrayStock'));
    }

    public function add()
    {
        // Get all parties
        $party_data = new Party;
        $arrayParties = $party_data->all_parties(1);

        // Get all Products
        $product_data = new Product;
        $arrayProducts = $product_data->all_products('p');

        // Get all Categories
        $category_data = new Category;
        $array_category = $category_data->all_category();        
        return View('purchase_stock.add', compact('arrayParties', 'arrayProducts', 'array_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        //print_r(Input::all()); die;
        //DB::transaction(function () {
        $rules = array(
            'category_id' => 'required',
            'party_id'  => 'required',
            'bilty_no'  => 'required',
            'adda_address'  => 'required',
            'quantity'  => 'required',
            'product_id' => 'required'
        );
        
        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        //$data = new PurchaseStockMaster();
        DB::transaction(function () {
        $date = date("Y-m-d H:i:s");    
        // Get COA
        $get_coa = new COA;
        // Insert in purchase master table
        $category_id = Input::get('category_id');
        $party_id = Input::get('party_id');
        $coa_code = $get_coa->party_coa($party_id);
        $bilty_no = Input::get('bilty_no');
        $total_quantity = Input::get('total_quantity');
        $grand_total = Input::get('grand_total');
        $bilty_no = Input::get('bilty_no');
        $total_item = Input::get('total_item');
        $user_id = Auth::user()->id;
        $adda_address = Input::get('adda_address');
        $purchase_date = date("Y-m-d",strtotime(Input::get('purchase_date')));
        $arrayInsert = array('party_id'         => $party_id, 
                                "category_id"    => $category_id,
                                "created_at"    => $date,
                                "purchase_date" => $purchase_date,
                                "bilty_no"      => $this->RemoveComma($bilty_no),
                                "total_quantity"=> $this->RemoveComma($total_quantity),
                                "grand_total"   => $this->RemoveComma($grand_total),
                                "total_item"    => $this->RemoveComma($total_item),
                                "adda_address"  => $adda_address,
                                "user_id"       => $user_id);
        //if($party_id != 0 && !empty($party_id))
        $last_stock_id = PurchaseStockMaster::insertGetId($arrayInsert);

        // Insert in sale detail table
        if($last_stock_id != 0 && $last_stock_id != "")
        {
            $product_id = Input::get('product_id');
            for($i=0; $i<count($product_id); $i++)
            {
                // Update all List price in-active
                $data = new ItemPrices();
                ItemPrices::where('product_id', Input::get("product_id.$i"))->update(
                [
                'is_active' => 1
                ]);
                // Insert in purchase details table
                $arrData[] = array( 
                            "stock_master_id"   => $last_stock_id,
                            "quantity"          => $this->RemoveComma(Input::get("quantity.$i")),
                            "product_id"        => Input::get("product_id.$i"),
                            "discount"          => $this->RemoveComma(Input::get("discount_id.$i")),
                            "net_price"         => $this->RemoveComma(Input::get("net_price.$i")), 
                            "list_price"        => $this->RemoveComma(Input::get("list_price.$i")),
                            "total_amount"      => $this->RemoveComma(Input::get("total_amount.$i")),
                            "created_at"        => $date               
                        );
                // For Stock Handling
                $arrDataStock[] = array( 
                            "purchase_stock_master_id" => $last_stock_id,
                            "product_credit"   => Input::get("quantity.$i"),
                            "product_id"       => Input::get("product_id.$i"), 
                            "category_id"      => $category_id,
                            "party_id"         => $party_id,
                            "sale_purchase_date" => $purchase_date,
                            "created_at"       => $date               
                        );
                // Update/Insert List Price
                $arrDataListPrice[] = array( 
                            "product_id" => Input::get("product_id.$i"),
                            "list_price" => Input::get("list_price.$i"),
                            "is_active"  => 0, 
                            "date_price" => $date               
                        );
            }
            ItemPrices::insert($arrDataListPrice);
            ProductStock::insert($arrDataStock);
            PurchaseStockDetail::insert($arrData);
            // Put all transections

            // Master
            $arrayInsertMaster = array('party_id'=> $party_id, 
                                "category_id"    => $category_id,
                                "purchase_stock_master_id"=> $last_stock_id,
                                "created_at"    => $date,
                                "voucher_type"  => "CP",
                                "purchase_date" => $purchase_date,
                                "bilty_no"      => $this->RemoveComma($bilty_no),
                                "total_quantity"=> $this->RemoveComma($total_quantity),
                                "grand_total"   => $this->RemoveComma($grand_total),
                                "total_item"    => $this->RemoveComma($total_item),
                                "adda_address"  => $adda_address,
                                "user_id"       => $user_id);
            $account_last_master_id = PurchaseVoucherMaster::insertGetId($arrayInsertMaster);
            // Insert in sales detail table
            $purchase_date = date("d-m-Y", strtotime($purchase_date));
            $purchase_descriptions = $purchase_date . " Purchase Invoice";
            $InventoryDebitAcc = "100000";
            $PartyCreditAcc = $coa_code;
            $DiscountCreditAcc = "100001";
            $arrTrans[] = array("coa" => $InventoryDebitAcc, "desc" => $purchase_descriptions,  "debit" => $this->RemoveComma($grand_total), "credit" => 0);
            $arrTrans[] = array("coa" => $PartyCreditAcc, "desc" => $purchase_descriptions,"debit" => 0, "credit" => $this->RemoveComma($grand_total));
            $arrTrans[] = array("coa" => $DiscountCreditAcc, "desc" => $purchase_descriptions,"debit" => 0, "credit" => 0); // Discount Amount
            foreach($arrTrans as $tran)
            {
                $arrayInsertDetail = array("account_stock_master_id" => $account_last_master_id,
                            "coa_code" => $tran["coa"],
                            "purchase_debit_amount" => $tran["debit"],
                            "purchase_descriptions" => $tran["desc"],
                            "purchase_stock_master_id" => $last_stock_id,
                            "purchase_credit_amount" => $tran["credit"]);
                PurchaseVoucherDetail::insert($arrayInsertDetail);
            }
        }
        }); // End transections 
        if(empty($id))
        {
            Session::flash('purchase_message', "Purchase Stock added successfully!");
            return Redirect::back();    
        }
        
    }

    // Remove commas in a numeric number
    public function RemoveComma($value)
    {
        return str_replace(",", "", $value);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            $arrayProducts = $product_data->all_products('p');

            // Get all Categories
            $category_data = new Category;
            $array_category = $category_data->all_category(); 

            // Master Data 
            $master_data = DB::table('purchase_stock_master')->where('id', $id)->first();
            // Details Data
            $detail_data = DB::table('purchase_stock_detail')
                              ->join('products', 'products.id', '=', 'purchase_stock_detail.product_id')  
                              ->select('purchase_stock_detail.*','products.name AS product_name')
                              ->where('stock_master_id', $id)->get();
            return View('purchase_stock.edit', compact('master_data','arrayParties','arrayProducts', 'detail_data', 'array_category'));
        }
        catch (TestimonialNotFoundException $e) {
            return Redirect::route('purchase_stock.edit')->with('error', 'Error Message');
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
        Session::flash('purchase_stock_edit', "Purchase Stock update successfully!");
        return redirect('purchase_stock');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = "", $from = "")
    {
        if(empty($id)) 
          $id = Input::get('DelID');
        // Remove all records
        $removeMaster = PurchaseStockMaster::where('id', '=', $id)->delete();
        $removeDetails = PurchaseStockDetail::where('stock_master_id', '=', $id)->delete();
        $removeMaster = ProductStock::where('purchase_stock_master_id', '=', $id)->delete();
        // Remove Vouchers
        $removeVoucherMaster = PurchaseVoucherMaster::where('purchase_stock_master_id', '=', $id)->delete();
        $removeVoucherDetail = PurchaseVoucherDetail::where('purchase_stock_master_id', '=', $id)->delete();

        if($from != "update")
        {
          echo "delete"; 
        }
        
    }
}
