<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Party extends Model
{
    protected $table = 'parties';
    // Get all Parties
    public function all_parties($id = null)
	{
		if(empty($id))
			$arrayParty = DB::table('parties')->orderBy('name', 'asc')->get();
		else
			$arrayParty = DB::table('parties')->where('type_id',$id)->orderBy('name', 'asc')->get();
		return $arrayParty;
	}
}
