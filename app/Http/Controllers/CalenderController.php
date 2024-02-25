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
        CURLOPT_URL => 'http://soap.netgsm.com.tr:8080/Sms_webservis/SMS?wsdl/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '<?xml version="1.0"?>
        <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                     xmlns:xsd="http://www.w3.org/2001/XMLSchema"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            <SOAP-ENV:Body>
                <ns3:smsGonderNNV2 xmlns:ns3="http://sms/">
                    <username>8503085771</username>
                    <password>Sedat73328.</password>
                    <header>mesajbaşlığı</header>
                    <msg>mesaj1</msg>
                    <gsm>5051313404</gsm>
                    <msg>mesaj2</msg>
                    <gsm>5051313404</gsm>
                    <filter>0</filter>
                    <encoding>TR</encoding>
                    <appkey>xxx</appkey>
                </ns3:smsGonderNNV2>
            </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/xml'
        ),
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
        $tarihMetni = Carbon::parse($tarihMetni);


        \DB::table("events")->insert([
          "title" => "ABONE", //$request->title,',
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
      $tarihMetni = Carbon::parse($tarihMetni);
      \DB::table("events")->insert([
        "title" => 'DOLU', //$request->title,//'Dolu,
        "sahaId" => $request->sahaId,
        "date" => $tarihMetni,
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
  public function apicalender($id, $addweek)
  {
    $halisaha = \DB::table("halisaha")->where("id", $id)->first();
    $userId = \Auth::user()->id;
    $allsaha = \DB::table("halisaha")->where("userId", $userId)->get();

    $acilissaati = $halisaha->starthour;
    $kapanissaati = $halisaha->endhour;
    $macsuresi = $halisaha->macsuresi;
    $offdays = $halisaha->offdays;

    $events = \DB::table("events")->where("sahaId", $id)->where("deleted", 0)->get();
    $appointments = json_decode($events, true);

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
    $now = \Carbon\Carbon::now();

    // Şu anki tarih ve saat
    if ($addweek > 0) {
      for ($i = 0; $i < $addweek; $i++) {
        $now = $now->addWeek();
      }
    } elseif ($addweek < 0) {
      for ($i = 0; $i > $addweek; $i--) {
        $now = $now->subWeek();
      }
    } else {
      $addweek = 0;
    }

    // Haftanın günlerini ve tarihlerini alalım
    $filteredDays = [];
    for ($i = \Carbon\Carbon::SUNDAY; $i <= \Carbon\Carbon::SATURDAY; $i++) {
      // Haftanın günlerini ve tarihlerini alırken isimlerini de alacağız
      $day = $now->copy()->startOfWeek()->addDays($i);
      $filteredDays[] = [
        'tarih' => $day->format('Y-m-d'),
        'gun_ismi' => $day->locale('tr')->dayName,
      ];
    }

    // Gerekli bilgileri döndür
    return response()->json([
      'halisaha' => $halisaha,
      'appointments' => $appointments,
      'acilissaati' => $acilissaati,
      'kapanissaati' => $kapanissaati,
      'macsuresi' => $macsuresi,
      'filteredDays' => $filteredDays,
      'offdays' => $offdays,
      'allsaha' => $allsaha,
      'events' => $events,
      'id' => $id,
      'reservationTimes' => $reservationTimes,
      'addweek' => $addweek
    ]);
  }



}
