<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class COA extends Model
{
    protected $table = "coa";

    // Get COA of party
    public function party_coa($id)
    {
    	$arrayCoa = DB::table('coa')->where('party_id',$id)->get();
		$coa = $arrayCoa[0]->coa_code;
		return $coa;
    }

    // Get all transections
    public function get_all_transection()
    {
    	$arrayTransection = DB::table('purchase_voucher_master')
    						->join('parties', 'parties.id', '=', 'purchase_voucher_master.party_id')
    						->join('categories', 'categories.id', '=', 'purchase_voucher_master.category_id')	
    						->select('purchase_voucher_master.*','parties.name AS party_name','categories.category_name AS category_name')	
    						->paginate(10);
    	return $arrayTransection;	
    }
}
