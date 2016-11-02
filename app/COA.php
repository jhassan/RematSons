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
}
