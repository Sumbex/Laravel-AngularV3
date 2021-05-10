<?php

namespace App\Console;

use Carbon\Carbon;
use App\SecReuniones;
use Illuminate\Support\Facades\DB;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:iniciar_reunion')->when(function () {
            $reunion = SecReuniones::select([
                'fecha_inicio',
                DB::raw("now() as fecha"),
                'estado_reunion_id as estado'
            ])
                ->latest('id')->first();

            if ($reunion->estado == 1) {
                $fechaReunion = Carbon::parse($reunion->fecha_inicio)->format('Y-m-d H:i');
                $fechaActual = Carbon::parse($reunion->fecha)->format('Y-m-d H:i');
                if ($fechaReunion == $fechaActual) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });

        $schedule->command('command:finalizar_reunion')->when(function () {
            $reunion = SecReuniones::select([
                'fecha_termino',
                DB::raw("now() as fecha"),
                'estado_reunion_id as estado'
            ])
                ->latest('id')->first();
            if ($reunion->estado == 3) {
                $fechaTermino = Carbon::parse($reunion->fecha_termino);
                $fechaTermino->addHours(48);
                /* $fechaTermino->addMinutes(5); */

                $fechaReunion = Carbon::parse($fechaTermino)->format('Y-m-d H:i');
                $fechaActual = Carbon::parse($reunion->fecha)->format('Y-m-d H:i');

                if ($fechaReunion == $fechaActual) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });

        $schedule->command('command:cumpleanio')->dailyAt('13:00');/*->everyMinute();*/
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
