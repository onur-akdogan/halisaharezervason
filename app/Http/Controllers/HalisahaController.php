<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HalisahaController extends Controller
{
   public function index()
   {
      $halisaha = \DB::table("halisaha")->where("userId", \Auth::user()->id)->get();

      return view("halisahalar.halisahalalist", compact("halisaha"))->with('success', 'Post created successfully');
   }
   public function delete($id)
   {
      \DB::table("halisaha")->where("id", $id)->delete();
      return redirect()->route("halisaha.index")->with('success', 'Silme İşlemi Başarılı');
   }
   public function addpage()
   {
      $jsonData = '[
      {"id":0, "name":"Pazar"},
      {"id":1, "name":"Pazartesi"},
      {"id":2, "name":"Salı"},
      {"id":3, "name":"Çarşamba"},
      {"id":4, "name":"Perşembe"},
      {"id":5, "name":"Cuma"},
      {"id":6, "name":"Cumartesi"}
  ]';

      // JSON verisini PHP dizisine çevirin
      $days = json_decode($jsonData, true);

      return view("halisahalar.halisahalaaddpage", compact("days"));
   }
   public function editpage($id)
   {



      $jsonData = '[
   {"id":0, "name":"Pazar"},
   {"id":1, "name":"Pazartesi"},
   {"id":2, "name":"Salı"},
   {"id":3, "name":"Çarşamba"},
   {"id":4, "name":"Perşembe"},
   {"id":5, "name":"Cuma"},
   {"id":6, "name":"Cumartesi"}
]';


      $halisahadata = \DB::table("halisaha")->where("id", $id)->first();

      // JSON verisini PHP dizisine çevirin
      $days = json_decode($jsonData, true);
      $selectedDays = json_decode($halisahadata->offdays, true);
      $macsuresi = str_replace(":00", "", $halisahadata->macsuresi);
      $macsuresi = str_replace("00:", "", $macsuresi);


      return view("halisahalar.halisahalaeditpage", compact("days", "selectedDays", "halisahadata", "macsuresi"));
   }
   public function add(Request $request)
   {
      $newoffdays = [];

      foreach ($request->offday as $offday) {
         $newoffdays[] = $offday;
      }
      $resultString = "[" . implode(",", $newoffdays) . "]";


      \DB::table("halisaha")->insert([
         "name" => $request->name,
         "userId" => \Auth::user()->id,
         "starthour" => $request->starthour . ":00",
         "endhour" => $request->endhour . ":00",

         "macsuresi" => "00:" . $request->macsuresi . ":00",
         "offdays" => $resultString,

      ]);
      return redirect()->route("halisaha.index")->with('success', 'Ekleme İşlemi Başarılı');

   }
   public function update(Request $request)
   {
      $newoffdays = [];

      foreach ($request->offday as $offday) {
         $newoffdays[] = $offday;
      }
      $resultString = "[" . implode(",", $newoffdays) . "]";


      \DB::table("halisaha")->where("id", $request->id)->update([
         "name" => $request->name,
         "userId" => \Auth::user()->id,
         "starthour" => $request->starthour . ":00",
         "endhour" => $request->endhour . ":00",

         "macsuresi" => "00:" . $request->macsuresi . ":00",
         "offdays" => $resultString,

      ]);
      return redirect()->route("halisaha.index")->with('success', 'Güncelleme İşlemi Başarılı');
      ;

   }

}
