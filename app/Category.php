<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
    protected $table = "categories";

    // Get all category
    public function all_category()
    {
    	$arrayCategory = DB::table('categories')->orderBy('category_name', 'asc')->get();
    	return $arrayCategory;
    }
}
