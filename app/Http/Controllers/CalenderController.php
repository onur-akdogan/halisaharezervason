<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Netgsm\Sms\SmsSend;
use Intervention\Image\Facades\Image as InterventionImage;

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
          'msgheader' => "8503085771",
          'gsm' => $event->userinfo,
          'message' => "Sayın " . $event->userName . " " . $user->name . " halısaha'da " . "" . $event->date . " maçınıza bekliyoruz. Halisaha iletişim:" . $user->phone,
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
    if ($request->aboneTime > 0) {
      $sms = new SmsSend;
      $data = array(
        'msgheader' => "8503085771",
        'gsm' => $request->userinfo,
        'message' => "Sayın " . $request->userName . ' ' . $user->name . ' isimli halısaha tarafından' . " aboneliğiniz " . $request->aboneTime / 4 . " aylık (" . $request->aboneTime . " Hafta) oluşturulmuştur. Halisaha iletişim:" . $user->phone,
        'filter' => '0',
        'startdate' => '270120230950',
        'stopdate' => '270120231030',
      );

    } else {
      $sms = new SmsSend;
      $data = array(
        'msgheader' => "8503085771",
        'gsm' => $request->userinfo,
        'message' => "Sayın " . $request->userName . ' ' . $user->name . ' isimli halısaha tarafından' . " maçınız " . '' . $tarihMetni . " tarihi için rezervasyon oluşturulmuştur. Halisaha iletişim:" . $user->phone,
        'filter' => '0',
        'startdate' => '270120230950',
        'stopdate' => '270120231030',
      );
    }
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
    $eventsget = \DB::table("events")->where("id", $id)->first();
    $halisaha = \DB::table("halisaha")->where("id", $eventsget->sahaId)->first();
    $username = \DB::table("users")->where("id", $halisaha->userId)->first();


    $tarihMetni = $eventsget->date;

    // Sonucu yazdırma
    $tarihMetni = Carbon::parse($tarihMetni);
    $sms = new SmsSend;
    $data = array(
      'msgheader' => "8503085771",
      'gsm' => $eventsget->userinfo,
      'message' => "Sayın " . $eventsget->userName . ' ' . $username->name . ' isimli halısaha tarafından' . " maçınız " . '' . $tarihMetni . " tarihi için rezervasyon iptal edilmiştir. Halisaha iletişim:" . $username->phone,
      'filter' => '0',
      'startdate' => '270120230950',
      'stopdate' => '270120231030',
    );

    $sonuc = $sms->smsgonder1_1($data);

    return redirect()->back()->with('success', 'İptal İşlemi Başarılı');

  }

  public function deleteback($id)
  {

    $events = \DB::table("events")->where("id", $id)->update(["deleted" => 0]);




    return redirect()->back()->with('success', 'İşlem Başarılı');

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


  public function downloadImages($halisaha)
  {
    $tableImage = InterventionImage::make(public_path('halisaha.jpg'));

    $today = Carbon::now()->startOfDay();
    $tomorrow = Carbon::tomorrow()->startOfDay();

    $halisaha = \DB::table("halisaha")->where("id", $halisaha)->first();
    $userName = \DB::table("users")->where("id", $halisaha->userId)->first();
    
    $acilissaati = $halisaha->starthour;
    $kapanissaati = $halisaha->endhour;
    $macsuresi = $halisaha->macsuresi;
    $offdays = $halisaha->offdays;

    $events = \DB::table("events")
      ->where("deleted", 0)
      ->whereDate("date", ">=", $today)
      ->whereDate("date", "<=", $tomorrow)
      ->get();
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
    $addweek = 0;

    // Haftanın günlerini ve tarihlerini alalım
    $filteredDays = [];

    // Bugünün tarihini al
    $bugun = \Carbon\Carbon::now()->format('Y-m-d');
    $yarın = \Carbon\Carbon::tomorrow()->format('Y-m-d');

    // Bugün ve yarını filtrelenmiş günler dizisine ekle
    $filteredDays[] = [
      'tarih' => $bugun,
      'gun_ismi' => \Carbon\Carbon::now()->locale('tr')->dayName,
    ];
    $filteredDays[] = [
      'tarih' => $yarın,
      'gun_ismi' => \Carbon\Carbon::tomorrow()->locale('tr')->dayName,
    ];

    $datesdolu = [];
    $datesbos = [];


    foreach ($reservationTimes as $reservation) {
      $reservationStartHour = (int) explode(':', $reservation["start"])[0];
      $reservationStartMinute = (int) explode(':', $reservation['start'])[1];

      // Saat ve dakikayı iki haneli olarak biçimlendirme
      $reservationStartHourFormatted = sprintf("%02d", $reservationStartHour);
      $reservationStartMinuteFormatted = sprintf("%02d", $reservationStartMinute);

      foreach ($filteredDays as $day) {
        $reserved = false;

        foreach ($events as $event) {
          // Tarih ve saatleri karşılaştırırken, dakika kısmını da ekleyin
          if ($event->date == $day["tarih"] . " " . $reservationStartHourFormatted . ":" . $reservationStartMinuteFormatted . ":00") {
            $datesdolu[] = $day["tarih"] . " " . $reservationStartHourFormatted . ":" . $reservationStartMinuteFormatted . ":00";
            $reserved = true;
          }
        }


        $datesbos[] = $day["tarih"] . " " . $reservationStartHourFormatted . ":" . $reservationStartMinuteFormatted . ":00";

      }
    }
    // Boş tarihlerden dolu tarihlerle eşleşenleri çıkar
    $filteredDatesBos = array_filter($datesbos, function ($dateBos) use ($datesdolu) {
      return !in_array($dateBos, $datesdolu);
    });

    

    $daysWithDates = [];
    foreach ($filteredDatesBos as $date) {
      $day = date('Y-m-d', strtotime($date));
      $daysWithDates[$day][] = $date;  
    }
    $index = 0; 
    $tableImage->text($userName->name,360, 60, function ($font) { 
      $font->file(public_path('ProtestStrike-Regular.ttf'));
      $font->size(50);
      $font->color('#fff');
      $font->align('center'); 
      $font->valign('center');
    }); 
    // Her bir günün tarihlerini ayrı ayrı gösterme 
    foreach ($daysWithDates as $day => $dates) {

      if ($index == 0) {
        $tableImage->text($day . ' | ' . '' . ' ' . "", 20, 150, function ($font) {
          $font->file(public_path('ProtestStrike-Regular.ttf'));
          $font->size(65);
          $font->color('#fff');
          $font->align('top-center');
          $font->valign('top');
        });
        $dateone = 2;
        foreach ($dates as $date) {
          $dateone++;
          list($reservationStartHour, $reservationStartMinute) = explode(':', substr($date, 11, 5));
          $formattedTime = $reservationStartHour . ':' . $reservationStartMinute . ' - ' . $reservationStartHour + 1 . ':' . $reservationStartMinute . ' ';

          $tableImage->text($formattedTime, 60, $dateone * 70, function ($font) {
            $font->file(public_path('ProtestStrike-Regular.ttf'));
            $font->size(44);
            $font->color('#fff');
            $font->align('center-left');
            $font->valign('top');
          });
        }
      } else {

        $tableImage->text(' ' . $day . '' . ' ' . "", 370, 150, function ($font) {
          $font->file(public_path('ProtestStrike-Regular.ttf'));
          $font->size(65);
          $font->color('#fff');
          $font->align('center-left');
          $font->valign('top');
        });
        $datetwo = 2;
        foreach ($dates as $date) {
          $datetwo++;
          list($reservationStartHour, $reservationStartMinute) = explode(':', substr($date, 11, 5));
          $formattedTime = $reservationStartHour . ':' . $reservationStartMinute . ' - ' . $reservationStartHour + 1 . ':' . $reservationStartMinute . ' ';

          $tableImage->text($formattedTime, 410, $datetwo * 70, function ($font) {
            $font->file(public_path('ProtestStrike-Regular.ttf'));
            $font->size(44);
            $font->color('#fff');
            $font->align('center-left');
            $font->valign('top');
          });
        }






      }
      $index++;

    }


    $tableImage->save(public_path('bosssaatler.jpg'));

    // Resmi tarayıcıya gönderme 
    $imageContent = $tableImage->encode('jpg');

    // Dosyayı sil
    $deleteImagePath = public_path('bosssaatler.jpg');

    // Başlık ekleyerek dosyayı indirme olarak ayarla
    $response = response($imageContent)->header('Content-Type', 'image/jpeg')
      ->header('Content-Disposition', 'attachment; filename="bosssaatler.jpg"');

    // Dosyayı kaydet
    $response->send();


    return $response;


  }

}