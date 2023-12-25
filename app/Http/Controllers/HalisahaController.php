<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HalisahaController extends Controller
{
  public function index(){
     $halisaha=\DB::table("halisaha")->where("userId",\Auth::user()->id)->get();
    return view("halisahalar.halisahalalist",compact("halisaha"));
  }
  public function addpage(){
    return view("halisahalar.halisahalaaddpage" );
 }

 public function add(Request $request){
    dd( $request->all());
    return view("halisahalar.halisahalaaddpage" );
 }

}
