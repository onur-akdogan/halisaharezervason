<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Netgsm\Sms\SmsSend;

use Illuminate\Http\Request;

class CalenderController extends Controller
{
  public function sms()
  {

    $nowTime = Carbon::now();
    $thirtyMinutesLater = $nowTime->copy()->addMinutes(30);

    $events = \DB::table("events")
      ->where("smsstatus", 0)
      ->where("deleted", 0)
      ->get();
    $msGsm = array();

    foreach ($events as $event) {
      $halisaha = \DB::table("halisaha")->where("id", $event->sahaId)->first();
      $user = \DB::table("users")->where("id", $halisaha->userId)->first();

      $eventDateTime = Carbon::parse($event->date);
      $now = Carbon::now();
      $diffInMinutes = $now->diffInMinutes($eventDateTime);
      // Eğer etkinlik tarihine 30 dakika veya daha az kaldıysa SMS gönder
      if ($now->diffInMinutes($eventDateTime) <= 30) {
        $sms = new SmsSend;
        $data = array(
          'msgheader' => "SEDAT AKSU",
          'gsm' => $event->userinfo,
          'message' => "Sayın " . $event->userName . " " . $user->name . " halısaha'da " . "" . $event->date . " maçınıza bekliyoruz.",
          'filter' => '0',
          'startdate' => '270120230950',
          'stopdate' => '270120231030',
        );

        $sonuc = $sms->smsgonder1_1($data);
        $events = \DB::table("events")
          ->where("id", $event->id)
          ->update([
            "smsstatus" => 1,
          ]);
      }
    }





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
    $halisaha = \DB::table("halisaha")->where("id", $request->sahaId)->first();
    $user = \DB::table("users")->where("id", $halisaha->userId)->first();
    $sms = new SmsSend;
    $data = array(
      'msgheader' => "SEDAT AKSU",
      'gsm' => $request->userinfo,
      'message' => "Sayın " . $request->userName . ' ' . $user->name . ' isimli halısaha tarafından' . " maçınız " . '' . $tarihMetni . " tarihi için rezervasyonunuz oluşturulmuştur.",
      'filter' => '0',
      'startdate' => '270120230950',
      'stopdate' => '270120231030',
    );

    $sonuc = $sms->smsgonder1_1($data);



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
