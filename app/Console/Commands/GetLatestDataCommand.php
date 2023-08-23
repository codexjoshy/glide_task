<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetLatestDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // retrieve data from the url
        $url = "http://standards-oui.ieee.org/oui/oui.csv";
        $response = Http::get($url);
        if ($response->successful()) {
            $csvData = $response->body();
            dd($csvData);
            // $csvData = file_get_contents($url);
            // parse the data into an array
            $rows = str_getcsv($csvData, "\n"); // Split the CSV into rows
            $data = [];
            foreach ($rows as $row) {
                $data[] = str_getcsv($row, ","); // Split each row into columns
            }
            // identify the different rows and columns

            // save the record in the respective table in the database
        }
    }
}
