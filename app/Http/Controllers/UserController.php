<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use PHPUnit\Framework\Constraint\IsEmpty;
use Carbon\Carbon;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index(): View
    {
        $users = User::paginate();

        return view('users.index', compact('users'));
    } 
    public function banks(){
        $banks = \DB::table("payment")->orderByDesc("id")->get();
        return view('users.banks', compact('banks'));
    }
    public function banksaddpage(){
               return view('users.banksadd');
              
        }
        public function banksdelete($id){
            \DB::table("payment")->where("id",$id)->delete();
            return redirect()->route("admin.banks");
           
     }
        
    public function banksadd(Request $request){ 
    \DB::table("payment")->insert([
            "bankname"=>$request->bankname,
            "username"=>$request->username,
            "iban"=>$request->iban,
            "iletisim"=>$request->iletisim,
 
            
        ]);
       return redirect()->route("admin.banks");
    }

    public function users()
    {
        $users = User::orderByDesc("created_at")->get();

        return view('users.users', compact('users'));
    }
    public function aktivasyonadd($id){
        $users = \DB::table("users")->where("id", $id)->first();

        // Kullanıcının "active" alanından tarih bilgisini alıp uygun formata dönüştürüyoruz
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $users->active);
        
        // Tarih bilgisini 365 gün artırarak güncelliyoruz
        $newDate = $date->addDays(365)->toDateTimeString(); // Eğer tarihi ve saati almak istiyorsanız toDateTimeString kullanmalısınız.
        
        // Kullanıcının bilgilerini güncelliyoruz
        $user = \DB::table("users")->where("id", $id)->update([
            "active" => $newDate,
        ]);
         return redirect()->back();
    }
    public function musteriler()
    {
        $sahalar = \DB::table("halisaha")
            ->where("userId", \Auth::user()->id)
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
