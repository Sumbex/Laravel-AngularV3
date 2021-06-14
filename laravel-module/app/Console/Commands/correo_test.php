<?php

namespace App\Console\Commands;

use App\Mail\correo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class correo_test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:correo';

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
        $email = 'Marii.96.solar@gmail.com';
        $mail = Mail::to($email)->send(new correo());

        var_dump($mail);
    }
}
