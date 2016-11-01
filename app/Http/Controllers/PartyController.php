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
use App\COA;
use Redirect;

class PartyController extends Controller
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
        $data = new Party;
        $arrayParties = $data->all_parties();
        return View('parties.index', compact('arrayParties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rules = array(
            'name'  => 'required|unique:parties|max:100',
            'phone_no'  => 'unique:parties|max:12',
            'type_id'  => 'required'
        );

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $data = new Party();
        
        $data->name = Input::get('name');
        $data->address = Input::get('address');
        $data->phone_no = Input::get('phone_no');
        $data->city = Input::get('city');
        $data->type_id = Input::get('type_id');
        $coa_credit = str_replace(",","",Input::get("coa_credit"));
        $coa_debit = str_replace(",","",Input::get("coa_debit"));
        $party = new Party();
        if($data->type_id == 1)
        {
            $coa_code = $party->get_next_coa('p');
            $account_type = 'p';
        }
        elseif($data->type_id == 2)
        {
            $coa_code = $party->get_next_coa('s');
            $account_type = 's';
        }
        else
        {
            $coa_code = $party->get_next_coa('g');
            $account_type = 's';
        }
        
        if($data->save()){
            $insertedId = $data->id;
            // Create Party COA
            $arrDataCOA[] = array( 
                        "coa_code"    => $coa_code,
                        "coa_account" => Input::get("name"),
                        "coa_credit"  => (int)$coa_credit, 
                        "coa_debit"   => (int)$coa_debit,
                        "account_type"=> $account_type,
                        "party_id"    => (int)$insertedId       
                    );
            COA::insert($arrDataCOA);
            Session::flash('message', "Party added successfully!");
            return Redirect::back();
        }
        else{
            return Redirect::back()->with('error', 'Error message');;
        }
    }

    public function add()
    {
        return View('parties.add');
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
            $parties = DB::table('parties')->where('id', $id)->first();
            $coa = DB::table('coa')->where('party_id', $id)->first();
            return View('parties.edit', compact('parties','coa'));
        }
        catch (TestimonialNotFoundException $e) {
            return Redirect::route('parties.edit')->with('error', 'Error Message');
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
            'name'  => 'required',
            'type_id'  => 'required'
        );

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $data = new Party();
        
        $data->name = Input::get('name');
        $data->address = Input::get('address');
        $data->phone_no = Input::get('phone_no');
        $data->city = Input::get('city');
        $data->type_id = Input::get('type_id');
        $coa_credit = str_replace(",","",Input::get("coa_credit"));
        $coa_debit = str_replace(",","",Input::get("coa_debit"));
        
        Party::where('id', $id)->update(
            [
            'name' => $data->name,
            'address' => $data->address,
            'phone_no' => $data->phone_no,
            'city' => $data->city,
            'type_id' => $data->type_id,
            ]);
        COA::where('party_id', $id)->update(
            [
            'coa_credit' => (int)$coa_credit,
            'coa_debit' => (int)$coa_debit
            ]);
        $arrayParties = DB::table('parties')->orderBy('id', 'desc')->get();
        Session::flash('message_update', "Party updated successfully!");
        return View('parties.index', compact('arrayParties'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        $DelID = Input::get('DelID');
        $removeParty = Party::where('id', '=', $DelID)->delete();
        $ID = Party::where('id', '=', $DelID)->first();
        if ($ID === null) 
           echo "delete"; 
        else
            echo "sorry";
    }
}
