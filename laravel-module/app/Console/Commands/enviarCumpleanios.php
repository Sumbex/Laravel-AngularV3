<?php

namespace App\Console\Commands;

use App\Mail\MailCumpleanios;
use Illuminate\Console\Command;
use App\Socios;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Socio_datos_basicos;

class enviarCumpleanios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cumpleanio';

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
        $socio =  Socios::all();

        foreach ($socio as $key) {
            $nacimiento = Carbon::parse($key->fecha_nacimiento)->format('d-m');
            $hoy = Carbon::now()->format('d-m');


            if($nacimiento === $hoy){

                $sdb = Socio_datos_basicos::where(['socio_id'=>$key->id])->first();
                
                if($sdb){
                    var_dump($sdb->email_2);
                    // return false;
                    var_dump('hora server: '.Carbon::now());
                    var_dump('nombre:'.$key->nombres.' '.$key->a_paterno.$nacimiento.' === '.$hoy.' = '. ($nacimiento === $hoy));
                    $nac = Carbon::parse($key->fecha_nacimiento)->format('d-m-Y');
                    $now = Carbon::now()->format('d/m/Y');
                    $mail = Mail::to($sdb->email_2)->send(new MailCumpleanios($key, $nac, $now));

                    var_dump($mail);
                }else{
                    var_dump("el socio no tiene sus datos basicos, de entonces no tiene correo asociado para enviar felicitaciones");
                }
                
            }

        }
    }
}
