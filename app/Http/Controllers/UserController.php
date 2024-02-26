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

        $oldusers = [];
        $users = [];
        $oldeventsall = [];
        $halisahalar = \DB::table("halisaha")
            ->where("userId", \Auth::user()->id)
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

        $users = [];




        $users = \DB::table("events")
            ->where("deleted", 1)
            ->get();





        return view('users.musteriiptal', compact('users'));
    }
    public function aboneler()
    {

        $users = [];




        $users = \DB::table("aboneler")
            ->get();





        return view('users.aboneler', compact('users'));
    }

}
