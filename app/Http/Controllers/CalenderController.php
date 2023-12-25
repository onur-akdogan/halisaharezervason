<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalenderController extends Controller
{
    public function index($id){
 
        $halisaha=\DB::table("halisaha")->where("id",$id)->first();
        $allsaha=\DB::table("halisaha")->where("userId",\Auth::user()->id)->get();

        $acilissaati=$halisaha->starthour;
        $kapanissaati=$halisaha->endhour;
        $macsuresi=$halisaha->macsuresi;
        $offdays=$halisaha->offdays;

   
 
        return view("calenders.calender",compact    ("acilissaati","kapanissaati","macsuresi","offdays","allsaha"));
    }
}
