<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Camera;
use App\Models\Detection;
use Carbon\Carbon;

class PollLprFiles extends Command
{
    protected $signature = 'lpr:poll';
    protected $description = 'Poll Uploads folder and import new LPR detection images every few seconds';

    public function handle()
    {
        $basePath = config('lpr.upload_path', '/Users/mahin/Desktop/Uploads');
        $this->info("Polling directory: $basePath");

        while (true) {
            $this->pollForNewFiles($basePath);
            sleep(10); // Wait for 10 seconds before next check
        }
    }

    /**
     * Polls for new files in the specified base directory.
     */
    public function pollForNewFiles($basePath)
    {
        foreach (glob($basePath . '/*', GLOB_ONLYDIR) as $cameraFolder) {
            $cameraName = basename($cameraFolder);
            $camera = Camera::firstOrCreate(['name' => $cameraName]);

            foreach (glob($cameraFolder . '/*.{jpg,jpeg,JPG,JPEG}', GLOB_BRACE) as $file) {
                $filename = basename($file);
                $meta = $this->parse($filename);

                if (!$meta) {
                    $this->warn("Skipping unrecognized filename: $filename");
                    continue;
                }

                // Check if the file already exists in the database
                if (Detection::where('file_path', $file)->exists()) {
                    continue;
                }

                // Insert into database
                $size = filesize($file);
                Detection::create([
                    'camera_id' => $camera->id,
                    'plate'     => $meta['plate'],
                    'ts'        => $meta['timestamp'],
                    'date_str'  => $meta['date_str'],
                    'time_str'  => $meta['time_str'],
                    'file_path' => $file,
                    'file_size' => $size
                ]);
            }
        }
    }

    /**
     * Parse the filename to extract metadata.
     */
    private function parse($filename)
    {
        $pattern = '/^(.+?)_([A-Z0-9-]+)_([0-9]{14,17})/i';

        if (!preg_match($pattern, $filename, $m)) {
            return null;
        }

        $camera = trim($m[1]);
        $plate  = trim($m[2]);
        $ts     = substr($m[3], 0, 14); // Ensure 14 digits

        try {
            $dt = Carbon::createFromFormat('YmdHis', $ts);
        } catch (\Exception $e) {
            return null;
        }

        return [
            'camera'    => $camera,
            'plate'     => $plate,
            'timestamp' => $dt,
            'date_str'  => $dt->format('Y-m-d'),
            'time_str'  => $dt->format('H:i:s'),
        ];
    }
}
