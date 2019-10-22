<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\SecReuniones;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FinalizarReunion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:finalizar_reunion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reunion = SecReuniones::select([
            'id',
            'fecha_inicio',
            'fecha_termino',
            DB::raw("now() as fecha"),
            'estado_reunion_id as estado'
        ])
            ->latest('id')->first(); /* ->get()->last(); */

        $reunion->estado_reunion_id = 4;
        $reunion->save();
    }
}
