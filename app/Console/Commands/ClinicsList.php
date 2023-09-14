<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class ClinicsList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ClinicsList:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-code' => 'com.postmodern.mobimedReact',
        ])->get( env('APP_URL').'api/newClinics',
            [
            ])->json();
        
        return Command::SUCCESS;
    }
}
