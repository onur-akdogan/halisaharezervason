<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


use Carbon\Carbon;
use Netgsm\Sms\SmsSend;

class UserApiController extends Controller
{
    public function getbanks()
    {
        $banks = \DB::table("payment")->orderByDesc("id")->get();

        return response()->json([
            'status' => 200,
            'data' => $banks
        ], 200);
    }
    public function register(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'phone' => 'required|string|unique:users,phone',
            ]);

            // Create a new user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->api_token = Str::random(60);
            $user->active = Carbon::now()->addDays(90);



            $user->save();

            // Return response with user data
            return response()->json([
                'status' => 200,
                'api_token' => $user->api_token,
                'username' => $user->name,
                'email' => $user->email,
                'id' => $user->id,
                'active' => $user->active,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
        }
    }
    public function login(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                $user->api_token = Str::random(60);
                $user->save();

                return response()->json([
                    'status' => 200,
                    'api_token' => $user->api_token,
                    'username' => $user->name,
                    'email' => $user->email,
                    'id' => $user->id,
                    'active' => $user->active,
                ]);
            }

            return response()->json([
                'status' => 401,
                'message' => 'Email veya şifrsi yanlış.'
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
        }
    }
    public function userGet()
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
            $allsaha = \DB::table("halisaha")->where("userId", $user->id)->first();

            return response()->json([
                'status' => 200,
                'user' => $user,
                'halisaha' => $allsaha,

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
        }

    }

    public function halisahagetAll($id, $addweek)
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }


            $halisaha = \DB::table("halisaha")->where("id", $id)->first();
            $userId = $user->id;
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


        } catch (\Throwable $th) {

            return response()->json([
                'status' => 404,
                'message' => 'İlk Önce Saha Ekleyin'
            ]);
        }


    }

    public function halisahadetail($id)
    {
        try {

            $halisahadata = \DB::table("halisaha")->where("id", $id)->first();
            $halisahakapanis = $halisahadata->endhour;
            $firstTwoCharacters = substr($halisahakapanis, 0, 2);
            $fark = $firstTwoCharacters - 24;
            if ($halisahakapanis >= 24) {
                // İlk iki rakamı değiştirmek için örnek bir kod
                $newFirstTwoCharacters = "0" . $fark; // Örnek olarak 08 olarak değiştirildi
                $newEndhour = $newFirstTwoCharacters . substr($halisahakapanis, 2);
                $halisahadata->endhour = $newEndhour;
            }
            // JSON verisini PHP dizisine çevirin
            $macsuresi = str_replace(":00", "", $halisahadata->macsuresi);
            $macsuresi = str_replace("00:", "", $macsuresi);

            return response()->json([
                'status' => 200,
                'halisahadata' => $halisahadata,
                'macsuresi' => $macsuresi,

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
        }
    }

    public function halisahadelete($id)
    {

        try {
            \DB::table("halisaha")->where("id", $id)->delete();

            return response()->json([
                'status' => 200,
                "Silme Başarılı"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
        }
    }

    public function halisahaadd(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }



            \DB::table("halisaha")->insert([

                "userId" => $user->id,
                "name" => $request->name,
                "starthour" => $request->starthour,
                "endhour" => $request->endhour,
                "macsuresi" => $request->macsuresi,
                "offdays" => "[]",


            ]);


            return response()->json([
                'status' => 200,
                "message" => "Ekleme Başarılı"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "message" => "İşlem Hatası "
            ], 409);
        }
    }
    public function halisahaedit(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }



            \DB::table("halisaha")->where("id", $request->id)->update([
                "name" => $request->name,

                "starthour" => $request->starthour,
                "endhour" => $request->endhour,
                "macsuresi" => $request->macsuresi,
            ]);


            return response()->json([
                'status' => 200,
                "Güncelleme Başarılı"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 409);
        }
    }
    public function musteriget()
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
            $sahalar = \DB::table("halisaha")
                ->where("userId", $user->id)
                ->pluck("id"); // saha id'lerini bir dizi olarak al

            $users = [];

            $events = \DB::table("events")
                ->select('userinfo')
                ->whereIn("sahaId", $sahalar)
                ->groupBy('userinfo') // userinfo'ya göre grupla
                ->orderByDesc('id')
                ->get();

            foreach ($events as $event) {
                $latestEvent = \DB::table("events")
                    ->whereIn("sahaId", $sahalar)
                    ->where('userinfo', $event->userinfo)
                    ->orderByDesc('id')
                    ->first();

                // Kullanıcı bilgisine göre son etkinliği al
                if ($latestEvent) {
                    $users[] = $latestEvent;
                }
            }



            return response()->json([
                'status' => 200,
                'data' => $users

            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
        }

    }

    public function iptalsget()
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
            $users = [];
            $halisaha = \DB::table("halisaha")->where("id", $user->sahaId)->first();
            $users = \DB::table("events")
                ->orderByDesc("id")
                ->where("sahaId", $halisaha->id)
                ->where("deleted", 1)
                ->get();
            return response()->json([
                'status' => 200,
                'data' => $users
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
        }

    }

    public function abones()
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
            $users = [];


            $halisaha = \DB::table("halisaha")->where("id", $user->sahaId)->first();

            $users = \DB::table("aboneler")
                ->where("sahaId", $halisaha->id)
                ->orderByDesc("id")
                ->get();



            return response()->json([
                'status' => 200,
                'data' => $users
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
        }

    }
    public function deleteback($id)
    {

        try {
            $events = \DB::table("events")->where("id", $id)->update(["deleted" => 0]);





            return response()->json([
                'status' => 200,
                'message' => "İşlem Başarılı"

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                'message' => "İşlem Başarısız"

            ]);
        }

    }
    public function eventdelete($id)
    {

        try {
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

            return response()->json([
                'status' => 200,
                'message' => "İşlem Başarılı"

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                'message' => "İşlem Başarısız"

            ]);
        }

    }
    public function getallhalisaha()
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
            $allsaha = \DB::table("halisaha")
                ->orderByDesc("id")
                ->where("userId", $user->id)->get();
            return response()->json([
                'status' => 200,
                'data' => $allsaha
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
        }
    }

    public function eventadd(Request $request)
    {

        try {
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

            return response()->json([
                'status' => 200,
                'data' => "Başarılı"
            ]);
        } catch (e) {
            return response()->json([
                'status' => 409,
                'data' => "Hata"
            ]);
        }

    }

    public function eventedit(Request $request)
    {

        try {
            \DB::table("events")->where('id', $request->id)->update([
                "sahaId" => $request->sahaId,
                "title" => $request->title,

                "userName" => $request->userName,
                "userinfo" => $request->userinfo,
                "note" => $request->note,
            ]);
            return response()->json([
                'status' => 200,
                'data' => "Başarılı"
            ]);
        } catch (e) {
            return response()->json([
                'status' => 409,
                'data' => "Hata"
            ]);
        }

    }














    public function profileupdate(Request $request)
    {

        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
            \DB::table("users")->where('id', $user->id)->update([
                "email" => $request->email,
                "name" => $request->name,

            ]);
            return response()->json([
                'status' => 200,
                'data' => "Başarılı"
            ]);
        } catch (e) {
            return response()->json([
                'status' => 409,
                'data' => "Hata"
            ]);
        }

    }

    public function passwordupdate(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
            \DB::table("users")->where('id', $user->id)->update([
                "password" => Hash::make($request->password),
            ]);
            return response()->json([
                'status' => 200,
                'data' => "Başarılı"
            ]);
        } catch (e) {
            return response()->json([
                'status' => 409,
                'data' => "Hata"
            ]);
        }

    }
    public function deleteaccound()
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
            $user->delete();
            return response()->json([
                'status' => 200,
                'data' => "Başarılı"
            ]);
        } catch (e) {
            return response()->json([
                'status' => 409,
                'data' => "Hata"
            ]);
        }

    }
  

    public function downloadImages($halisaha)
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
            $tableImage = InterventionImage::make(public_path('halisaha.jpg'));

            $today = Carbon::now()->startOfDay();
            $tomorrow = Carbon::tomorrow()->startOfDay();

            $halisaha = \DB::table("halisaha")->where("id", $halisaha)->first();

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


        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                'data' => "Hata"
            ]);
        }
    }



}
