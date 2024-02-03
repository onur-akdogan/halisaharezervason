<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

class CalenderController extends Controller
{
  public function sms()
  { 
   $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.netgsm.com.tr/bulkhttppost.asp',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('usercode' => '8503085771','password' => 'X4.M4R3r','gsmno' => '5545693062','message' => 'testmesajı','msgheader' => 'SEDAT AKSU','filter' => '0','startdate' => '230520221650','stopdate' => '230520221830'),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
   }
  public function index($id)
  {

    $halisaha = \DB::table("halisaha")->where("id", $id)->first();
    $allsaha = \DB::table("halisaha")->where("userId", \Auth::user()->id)->get();

    $acilissaati = $halisaha->starthour;
    $kapanissaati = $halisaha->endhour;
    $macsuresi = $halisaha->macsuresi;
    $offdays = $halisaha->offdays;

    $events = \DB::table("events")->where("sahaId", $id)->where("deleted", 0)->get();
    return view("calenders.calender", compact("acilissaati", "kapanissaati", "macsuresi", "offdays", "allsaha", "events", 'id'));
  }
  public function add(Request $request)
  {
    $tarihMetni = $request->date;
    if ($request->aboneTime > 0) {
      for ($i = 0; $i <= $request->aboneTime; $i++) {

        // Sonucu yazdırma


        \DB::table("events")->insert([
          "title" => "Abone", //$request->title,',
          "sahaId" => $request->sahaId,
          "date" => $tarihMetni,
          "userName" => $request->userName,
          "userinfo" => $request->userinfo,
          "note" => $request->note,


        ]);

        $tarihMetni = Carbon::parse($tarihMetni);

        $tarihMetni->addWeek();
      }

      \DB::table("aboneler")->insert([
        "sahaId" => $request->sahaId,
        "startdate" => $request->date,
        "enddate" => $tarihMetni,
        "userName" => $request->userName,
        "userinfo" => $request->userinfo,
        "note" => $request->note,


      ]);

    } else {
      \DB::table("events")->insert([
        "title" => 'Dolu', //$request->title,//'Dolu,
        "sahaId" => $request->sahaId,
        "date" => $request->date,
        "userName" => $request->userName,
        "userinfo" => $request->userinfo,
        "note" => $request->note,


      ]);
    }

    //  return redirect()->route("calenders.calender")->with('success', 'Ekleme İşlemi Başarılı');

  }

  public function update(Request $request)
  {
    \DB::table("events")->where('id', $request->id)->update([
      "title" => $request->title,
      "sahaId" => $request->sahaId,
      "userName" => $request->userName,
      "userinfo" => $request->userinfo,
      "note" => $request->note,


    ]);
    //  return redirect()->route("calenders.calender")->with('success', 'Ekleme İşlemi Başarılı');

  }
  public function delete($id)
  {
    $events = \DB::table("events")->where("id", $id)->update(["deleted" => 1]);
    return redirect()->back()->with('success', 'Silme İşlemi Başarılı');

  }
  public function apicalender($id)
  {
    // Halisaha bilgilerini tek seferde al
    $halisaha = \DB::table('halisaha')
      ->where('id', $id)
      ->select('starthour', 'endhour', 'macsuresi', 'offdays', 'id')
      ->first();

    // Kullanıcıya ait tüm sahaları al
    $allsaha = \DB::table('halisaha')
      ->where('userId', \Auth::user()->id)
      ->get();

    // Events sorgusunu optimize et
    $events = \DB::table('events')
      ->where('sahaId', $id)
      ->where('deleted', 0)
      ->get();
    foreach ($events as $item) {
      if ($item->title == 'Abone') {
        $item->color = 'red';

      } else {
        $item->color = 'blueaccent';
      }
    }


    // Gerekli bilgileri döndür
    return response()->json([
      'halisaha' => $halisaha,
      'allsaha' => $allsaha,
      'events' => $events
    ]);
  }



}
