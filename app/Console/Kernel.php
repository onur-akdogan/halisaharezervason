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
            $nowTime = \Carbon\Carbon::now();
            $thirtyMinutesLater = $nowTime->copy()->addMinutes(30);

            $events = \DB::table("events")

                ->get();

            $msGsm = array();

            foreach ($events as $event) {
                $halisaha = \DB::table("halisaha")->where("id", $event->sahaId)->first();
                $msGsm[] = array('gsm' => $event->userinfo, 'message' => "Sayın " . $event->userName . '' . $halisaha->name . " Maçınız Saat " . '' . $event->date . " Başlayacaktır.");
            }




            $data = array('startdate' => '170220231210', 'stopdate' => '170220231300', 'header' => 'SEDAT AKSU', 'filter' => 0);
            $sms = new SmsSend;
            $cevap = $sms->smsGonderNN($msGsm, $data);
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
