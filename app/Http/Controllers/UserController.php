<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use PHPUnit\Framework\Constraint\IsEmpty;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::paginate();

        return view('users.index', compact('users'));
    }

    public function users()
    {
        $users = User::orderByDesc("created_at")->get();

        return view('users.users', compact('users'));
    }
    public function musteriler()
    {
        $sahalar = \DB::table("halisaha")
        ->where("userId", \Auth::user()->id)
        ->orderByDesc('id')
        ->pluck("id"); // saha id'lerini bir dizi olarak al

        $oldusers = [];
        $users = [];
        $oldeventsall = [];
        $halisahalar = \DB::table("halisaha")
            ->where("userId", \Auth::user()->id)
            ->get();

    
      
            $events = \DB::table("events")
                ->select('userinfo')
                ->orderByDesc('id')

                ->whereIn("sahaId", $sahalar)
                ->groupBy('userinfo')  // Include 'userName' in GROUP BY
                ->get();
            $eventsall = \DB::table("events")
            ->whereIn("sahaId", $sahalar)
                ->orderByDesc('id')
                ->get();


            foreach ($eventsall as $item) {
                $oldeventsall[] = $item;

            }
            foreach ($events as $event) {
                $oldusers[] = $event;

            }

   

        foreach ($oldeventsall as $item) {

            foreach ($oldusers as $event) {
                if ($event->userinfo == $item->userinfo) {
                    if(  $users == [] ){
                        $users[] = $item;
                    }else{
                        
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


        return view('users.musteri', compact('users'));
    }
    public function musterileriptal()
    {
 
        $sahalar = \DB::table("halisaha")
        ->where("userId", \Auth::user()->id)
        ->orderByDesc('id')
        ->pluck("id"); // saha id'lerini bir dizi olarak al
    
        $users = [];




        $users = \DB::table("events")
        ->whereIn("sahaId", $sahalar) // saha id'leri içinde olan aboneleri al

            ->where("deleted", 1)
            
            ->orderByDesc('id')
            ->get();





        return view('users.musteriiptal', compact('users'));
    }
    public function aboneler()
    {
        
        $sahalar = \DB::table("halisaha")
        ->where("userId", \Auth::user()->id)
        ->orderByDesc('id')
        ->pluck("id"); // saha id'lerini bir dizi olarak al
    
    $users = \DB::table("aboneler")
        ->whereIn("sahaId", $sahalar) // saha id'leri içinde olan aboneleri al
        ->orderByDesc('id')
        ->get();

        return view('users.aboneler', compact('users'));
    }

}
