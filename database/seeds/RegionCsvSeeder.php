<?php
use Flynsarmy\CsvSeeder\CsvSeeder;
//use Flynsarmy\CsvSeeder\CsvtoArray;

use Illuminate\Database\Seeder;

class RegionCsvSeeder extends CsvSeeder
{

	public function __construct()
    {
        $this->table = 'ms_regions';
        $this->filename = base_path() . '/database/seeds/csv/region.csv';
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Csv = new \App\Helpers\CsvToArray();
        $file = base_path() . '/database/seeds/csv/region.csv';
        $header = array('id', 'code', 'name_area', 'telp', 'alias', 'address', 'active');
        $data = $Csv->csv_to_array($file, $header);
        $collection = collect($data);
        foreach ($collection->chunk(50) as $chunk) {
            \DB::table('ms_regions')->insert($chunk->toArray());
        }
    }
}
