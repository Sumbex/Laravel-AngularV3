<?php

namespace App\Console\Commands;

use App\SecReuniones;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IniciarReunion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:iniciar_reunion';

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
            DB::raw("now() as fecha")
        ])
            /* $reunion = SecReuniones::where([
                'activo' => 'S'
            ])
                ->get()->last(); */
            ->get()->last();

        $test = 'fecha_inicio: ' . $reunion->fecha_inicio . ' / fecha_actual: ' . $reunion->fecha;
        echo "$test \n";
    }
}
