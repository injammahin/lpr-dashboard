<?php
// app/Jobs/RunLprScan.php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class RunLprScan implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public function handle()
    {
        // This will run the 'lpr:scan' command in the background
        Artisan::call('lpr:scan');
    }
}
