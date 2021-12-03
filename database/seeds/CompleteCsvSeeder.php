<?php
use Flynsarmy\CsvSeeder\CsvSeeder;
//use Flynsarmy\CsvSeeder\CsvtoArray;

use Illuminate\Database\Seeder;

class CompleteCsvSeeder extends CsvSeeder
{

	public function __construct()
    {
        $this->table = 'ms_regions';
        $this->filename = base_path() . '/database/seeds/csv/upload.csv';
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Csv = new \App\Helpers\CsvToArray();
        $file = base_path() . '/database/seeds/csv/upload.csv';
        $header = array('is_active', 'code', 'name_area', 'telp', 'alias', 'address', 'active');
        $data = $Csv->csv_to_array($file, $header);
        $collection = collect($data);
        foreach ($collection->chunk(50) as $chunk) {
            $user = \DB::table('users')->insert($chunk->toArray());
        }
    }
}
