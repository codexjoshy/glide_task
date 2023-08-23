<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\OuiData;

class ImportOuiData extends Command
{
    protected $signature = 'import:ouidata';
    protected $description = 'Import the latest IEEE OUI data from CSV into the database';

    public function handle()
    {
        $csvUrl = 'http://standards-oui.ieee.org/oui/oui.csv';

        $response = Http::timeout(90)->get($csvUrl);
        $csvData = $response->body();

        $csvPath = storage_path('app/temp.csv');
        Storage::put('temp.csv', $csvData);

        $csv = fopen($csvPath, 'r');

        $batchSize = 100;
        $batchData = [];

        // Empty the table before importing
        OuiData::truncate();
        $k = 0;
        while (($line = fgetcsv($csv)) !== false) {
            // skip first row
            if ($k > 0) {
                $batchData[] = [
                    'registry' => $line[0],
                    'assignment' => $line[1],
                    'organisation_name' => $line[2],
                    'organisation_address' => $line[3],
                    'created_at'=>now()
                ];

                // Insert batch data
                if (count($batchData) >= $batchSize) {
                    OuiData::insert($batchData);
                    $batchData = [];
                }
            }
            $k++;
        }

        // Insert any remaining data in the batch
        if (!empty($batchData)) {
            OuiData::insert($batchData);
        }

        fclose($csv);
        Storage::delete('temp.csv');

        $this->info('OUI data imported successfully.');
    }
}
