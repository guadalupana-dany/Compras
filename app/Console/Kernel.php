<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\cronStock'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //('custom:command) nombre del cron
       // $schedule->command('custom:command')
      //  ->daily();//cuando desees ejecutar la revision

     // $schedule->command('custom:command')->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

//* * * * * cd /C:/xampp/htdocs/sisPlanilla && php artisan schedule:run >> /dev/null 2>&1
/*
https://www.youtube.com/watch?v=ACEWD7XUwj4 como crear las tareas
php artisan make:command cronStock adicional --command=custom:command
crea una carpeta en app/console/commands
 public function handle()
    {
        //write here the code
    }

    ahora en el App/Consoleo/Kernel.php
     protected $commands = [
        //registrar aqui el cronStock
        'App\Console\Commands\cronStock'
    ];

pagina de como configurar tareas en windows 10
https://quantizd.com/how-to-use-laravel-task-scheduler-on-windows-10/

*/
