<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use Validator;
use Input;
use Session;
use App\Product;
use Redirect;

class ProductController extends Controller
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
        $data = new Product;
        $arrayProduct = $data->all_products('p');
        return View('products.index', compact('arrayProduct'));
    }

    public function add()
    {
        return View('products.add');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rules = array(
            'name'  => 'required|unique:products|max:100',
        );

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $data = new Product();
        
        $data->name = Input::get('name');
        
        if($data->save()){
            Session::flash('message', "Product added successfully!");
            return Redirect::back();
        }
        else{
            return Redirect::back()->with('error', 'Error message');;
        }
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
            $products = DB::table('products')->where('id', $id)->first();
            return View('products.edit', compact('products'));
        }
        catch (TestimonialNotFoundException $e) {
            return Redirect::route('products.edit')->with('error', 'Error Message');
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
        $rules = array(
            'name'  => 'required'
        );

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $data = new Product();
        
        $data->name = Input::get('name');
        
        Product::where('id', $id)->update(
            [
            'name' => $data->name
            ]);
        $arrayProduct = DB::table('products')->orderBy('id', 'desc')->get();
        Session::flash('message_update', "Party updated successfully!");
        return View('products.index', compact('arrayProduct'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $DelID = Input::get('DelID');
        $vouchermaster = Product::where('id', '=', $DelID)->delete();
        $ID = Product::where('id', '=', $DelID)->first();
        if ($ID === null) 
           echo "delete"; 
        else
            echo "sorry";
    }
}
