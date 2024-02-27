<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserApiController extends Controller
{

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
            $user->save();

            // Return response with user data
            return response()->json([
                'status' => 200,
                'api_token' => $user->api_token,
                'username' => $user->name,
                'email' => $user->email,
                'id' => $user->id
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
                    'id' => $user->id
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
            $allsaha = \DB::table("halisaha")->where("userId", $user->id)->get();

            return response()->json([
                'status' => 200,
                'user' => $user,
                'halisaha' => $user,

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
            $newoffdays = [];
            if ($request->offday != null) {
                foreach ($request->offday as $offday) {
                    $newoffdays[] = $offday;
                }
            }
            $resultString = "[" . implode(",", $newoffdays) . "]";
            $endhour = $request->endhour;

            $endhour = $request->endhour;
            $firstTwoCharacters = substr($endhour, 0, 2);
            $endhour = $request->endhour;
            $lastTwoCharacters = substr($endhour, -2);

            if ($firstTwoCharacters < "06") {

                $firstTwoCharacters = 24 + intval($request->endhour);
                $firstTwoCharacters = $firstTwoCharacters . ":" . $lastTwoCharacters . ":00";
            } else {
                $firstTwoCharacters = $firstTwoCharacters . ":" . $lastTwoCharacters . ":00";

            }



            if (strlen($request->starthour) == 8) {
                $newstart = $request->starthour;

            } else {
                $newstart = $request->starthour . ":00";

            }

            \DB::table("halisaha")->where("id", $request->id)->update([
                "name" => $request->name,
                "userId" => $user->id,
                "starthour" => $newstart,
                "endhour" => $firstTwoCharacters,

                "macsuresi" => "00:" . $request->macsuresi . ":00",
                "offdays" => $resultString,
            ]);



            return response()->json([
                'status' => 200,
                "Güncelleme Başarılı"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 409,
                "İşlem Hatası "
            ], 200);
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
            $oldusers = [];
            $users = [];
            $oldeventsall = [];
            $halisahalar = \DB::table("halisaha")
                ->where("userId", $user->id)
                ->get();

            foreach ($halisahalar as $item) {

                $events = \DB::table("events")
                    ->select('userinfo')
                    ->where("sahaId", $item->id)
                    ->groupBy('userinfo')  // Include 'userName' in GROUP BY
                    ->get();
                $eventsall = \DB::table("events")
                    ->where("sahaId", $item->id)
                    ->get();


                foreach ($eventsall as $item) {
                    $oldeventsall[] = $item;

                }
                foreach ($events as $event) {
                    $oldusers[] = $event;

                }

            }

            foreach ($oldeventsall as $item) {

                foreach ($oldusers as $event) {
                    if ($event->userinfo == $item->userinfo) {
                        if ($users == []) {
                            $users[] = $item;
                        } else {

                            foreach ($users as $user) {
                                if ($user->userinfo == $item->userinfo) {


                                } else {
                                    $users[] = $item;
                                }
                            }

                        }

                    }

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
            $users = \DB::table("events")
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




            $users = \DB::table("aboneler")
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


}
