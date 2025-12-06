<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Camera;
use App\Models\Detection;
use Carbon\Carbon;

class ScanLprFiles extends Command
{
    protected $signature = 'lpr:scan';
    protected $description = 'Scan Uploads folder and import new LPR detection images';

    public function handle()
    {
        $basePath = config('lpr.upload_path', '/Users/mahin/Desktop/Uploads');

        if (!is_dir($basePath)) {
            $this->error("Upload path not found: $basePath");
            return;
        }

        foreach (glob($basePath . '/*', GLOB_ONLYDIR) as $cameraFolder) {

            $cameraName = basename($cameraFolder);
            $camera = Camera::firstOrCreate(['name' => $cameraName]);

            foreach (glob($cameraFolder . '/*', GLOB_ONLYDIR) as $dateFolder) {

                foreach (glob($dateFolder . '/*.{jpg,jpeg,JPG,JPEG}', GLOB_BRACE) as $file) {

                    // Prevent half-written files
                    $size1 = filesize($file);
                    usleep(300000); // 0.3s
                    $size2 = filesize($file);
                    if ($size1 !== $size2) continue;

                    $filename = basename($file);
                    $meta = $this->parse($filename);

                    if (!$meta) {
                        $this->warn("Skipping unrecognized filename: $filename");
                        continue;
                    }

                    Detection::firstOrCreate(
                        ['file_path' => $file],
                        [
                            'camera_id' => $camera->id,
                            'plate'     => $meta['plate'],
                            'ts'        => $meta['timestamp'],
                            'date_str'  => $meta['date_str'],
                            'time_str'  => $meta['time_str'],
                            'file_size' => $size2
                        ]
                    );
                }
            }
        }

        $this->info("Scan complete at " . now());
    }


    /**
     * Parse filename: CAMERA1_ABC123_20251205143012_DETECT.jpeg
     */
    private function parse($filename)
    {
        $pattern = '/^(.+?)_([A-Z0-9-]+)_([0-9]{14,17})/i';

        if (!preg_match($pattern, $filename, $m)) {
            return null;
        }

        $camera = trim($m[1]);
        $plate  = trim($m[2]);
        $ts     = substr($m[3], 0, 14); // ensure 14 digits

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
