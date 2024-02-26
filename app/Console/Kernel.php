<?php

namespace App\Console;

use Carbon\Carbon;
use Netgsm\Sms\SmsSend;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
       
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
     return true;

        })->everyMinute(); // this query will run every month, read official documentation for detail

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
