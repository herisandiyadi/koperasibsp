<?php

use Illuminate\Database\Seeder;

class BranchCsvSeeder extends Seeder
{
    public function __construct()
    {
        $this->table = 'ms_projects';
        $this->filename = base_path() . '/database/seeds/csv/project.csv';
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Csv = new \App\Helpers\CsvToArray();
        $file = base_path() . '/database/seeds/csv/branch.csv';
        $header = array('id', 'branch_code', 'branch_name', 'region_id',  'address', 'telp', 'status');
        $data = $Csv->csv_to_array($file, $header);
        $collection = collect($data);
        foreach ($collection->chunk(50) as $chunk) {
            \DB::table('ms_branchs')->insert($chunk->toArray());
        }
    }
}
