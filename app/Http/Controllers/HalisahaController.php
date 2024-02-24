<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;

class HalisahaController extends Controller
{
   public function index()
   {
      try {
         $halisahasid = \DB::table("halisaha")->where("userId", \Auth::user()->id)->first();
         $id = $halisahasid->id;
         $halisahas = \DB::table("halisaha")->where("id", $id)->first();
         $allsaha = \DB::table("halisaha")->where("userId", \Auth::user()->id)->get();

         $acilissaati = $halisahas->starthour;
         $kapanissaati = $halisahas->endhour;
         $macsuresi = $halisahas->macsuresi;
         $offdays = $halisahas->offdays;

         $events = \DB::table("events")->where("sahaId", $id)->where("deleted", 0)->get();
         $halisaha = \DB::table("halisaha")->where("userId", \Auth::user()->id)->get();

         $appointments = json_decode($events, true);

         $jsonData = '[
      {"id":0, "name":"Pazar"},
      {"id":1, "name":"Pazartesi"},
      {"id":2, "name":"Salı"},
      {"id":3, "name":"Çarşamba"},
      {"id":4, "name":"Perşembe"},
      {"id":5, "name":"Cuma"},
      {"id":6, "name":"Cumartesi"}
  ]';

 
         // JSON verisini diziye dönüştürme
         $days = json_decode($jsonData, true);

 

         // Açılış ve kapanış saatlerini Carbon nesnelerine dönüştürme
         $openingTime = \Carbon\Carbon::createFromFormat('H:i:s', $acilissaati);
         
         $closingTime = \Carbon\Carbon::createFromFormat('H:i:s', $kapanissaati);

         // Rezervasyon aralığını Carbon nesnesine dönüştürme
         $reservationInterval = \Carbon\CarbonInterval::createFromFormat('H:i:s', $macsuresi);
       

         // Rezervasyon sürelerini liste olarak oluşturma
         $reservationTimes = [];
         $currentReservationTime = $openingTime->copy();

         while ($currentReservationTime->lte($closingTime)) {
            $reservationStart = $currentReservationTime->format('H:i:s');
            $currentReservationTime->add($reservationInterval);
            $reservationEnd = $currentReservationTime->format('H:i:s');
            $reservationTimes[] = ["start" => $reservationStart, "end" => $reservationEnd];
         }

         $now = Carbon::now();

         // Haftanın günlerini ve tarihlerini alalım
         $filteredDays = [];
         for ($i = Carbon::SUNDAY; $i <= Carbon::SATURDAY; $i++) {
             // Haftanın günlerini ve tarihlerini alırken isimlerini de alacağız
             $gun = $now->copy()->startOfWeek()->addDays($i);
             $filteredDays[] = [
                 'tarih' => $gun->format('Y-m-d'),
                 'gun_ismi' => $gun->locale('tr')->dayName,
             ];
         }
         $addweek=0;
  

         return view("halisahalar.halisahalistnew", compact("halisaha","appointments", "acilissaati", "kapanissaati","addweek", "macsuresi", "filteredDays", "offdays", "allsaha", "events", 'id', "reservationTimes"))->with('success', 'Post created successfully');

      } catch (\Throwable $th) {
       
         return redirect()->route('halisaha.addpage')->with('success', 'Lütfen İlk Olarak Saha Ekleyin!');
      }
   }
   public function allindex()
   {
      try {
         $halisahasid = \DB::table("halisaha")->first();
         $id = $halisahasid->id;
         $halisahas = \DB::table("halisaha")->where("id", $id)->first();
         $allsaha = \DB::table("halisaha")->where("userId", \Auth::user()->id)->get();

         $acilissaati = $halisahas->starthour;
         $kapanissaati = $halisahas->endhour;
         $macsuresi = $halisahas->macsuresi;
         $offdays = $halisahas->offdays;

         $events = \DB::table("events")->where("sahaId", $id)->where("deleted", 0)->get();
         $halisaha = \DB::table("halisaha")->where("userId", \Auth::user()->id)->get();

         return view("halisahalar.halisahalalists", compact("halisaha",  "acilissaati", "kapanissaati", "macsuresi", "offdays", "allsaha", "events", 'id'))->with('success', 'Post created successfully');

      } catch (\Throwable $th) {
         return redirect()->route('halisaha.addpage')->with('success', 'Lütfen İlk Olarak Saha Ekleyin!');
      }

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
      if ($request->offday != null) {
         foreach ($request->offday as $offday) {
            $newoffdays[] = $offday;
         }
      }

      $resultString = "[" . implode(",", $newoffdays) . "]";

      $endhour = $request->endhour;
      if ($request->endhour < "06") {

         $endhour = 24 + intval($request->endhour);
         $endhour = $endhour . ":00";
      }
      \DB::table("halisaha")->insert([
         "name" => $request->name,
         "userId" => \Auth::user()->id,
         "starthour" => $request->starthour . ":00",
         "endhour" => $endhour . ":00",

         "macsuresi" => "00:" . $request->macsuresi . ":00",
         "offdays" => $resultString,

      ]);
      return redirect()->route("halisaha.index")->with('success', 'Ekleme İşlemi Başarılı');

   }
   public function update(Request $request)
   {
      $newoffdays = [];
      if ($request->offday != null) {
         foreach ($request->offday as $offday) {
            $newoffdays[] = $offday;
         }
      }

      $resultString = "[" . implode(",", $newoffdays) . "]";

      if (strlen($request->starthour) == 8) {
         \DB::table("halisaha")->where("id", $request->id)->update([
             "name" => $request->name,
             "userId" => \Auth::user()->id,
             "starthour" => $request->starthour,
             "endhour" => $request->endhour,
     
             "macsuresi" => "00:" . $request->macsuresi . ":00",
             "offdays" => $resultString,
         ]);
     } else {
         \DB::table("halisaha")->where("id", $request->id)->update([
             "name" => $request->name,
             "userId" => \Auth::user()->id,
             "starthour" => $request->starthour . ":00",
             "endhour" => $request->endhour . ":00",
     
             "macsuresi" => "00:" . $request->macsuresi . ":00",
             "offdays" => $resultString,
         ]);
     }
      return redirect()->route("halisaha.index")->with('success', 'Güncelleme İşlemi Başarılı');
      ;

   }

}
