<?php
use Flynsarmy\CsvSeeder\CsvSeeder;
// use Flynsarmy\CsvSeeder\CsvtoArray;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProjectCsvSeeder extends CsvSeeder
{

	public function __construct()
    {
        $this->table = 'ms_projects';
        $this->filename = base_path() . '/database/seeds/csv/project_new.csv';
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Csv = new \App\Helpers\CsvToArray();
        $file = base_path() . '/database/seeds/csv/project_new.csv';
//        $header = array('id', 'region_id' ,'region_code', 'project_code', 'project_name', 'address', 'start_date', 'end_date', 'contract_number', 'description', 'date_salary', 'total_member', 'upload', 'status');
        $header = array('id', 'project_code', 'region', 'project_name', 'address', 'start_date', 'end_date', 'status', 'date_salary');

        $data = $Csv->csv_to_array($file, $header);
//        dd($data);

//		foreach($data as $key => $field){
//
//			try {
//				$date = Carbon::createFromFormat('d/m/Y',$data[$key]['end_date']);
//			} catch (\Throwable $exception) {
//				$date = $data[$key]['end_date'];
//			}
//			$data[$key]['end_date'] = Carbon::parse($date)->format('Y-m-d');
//		}
//
//		foreach($data as $key => $field){
//
//			try {
//				$date = Carbon::createFromFormat('d/m/Y',$data[$key]['start_date']);
//			} catch (\Throwable $exception) {
//				$date = $data[$key]['start_date'];
//			}
//			$data[$key]['start_date'] = Carbon::parse($date)->format('Y-m-d');
//		}

		// dd($data);
        $collection = collect($data);
        $regions = $collection->map(static function ($q){
            $region = \App\Region::where('name_area', $q['region'])->first();

            $start_date = null;
            $end_date = null;

            if($q['status'] != 'PERMANENT'){
                $start_date = Carbon::parse($q['start_date'])->format('Y-m-d');
                $end_date = Carbon::parse($q['end_date'])->format('Y-m-d');
            }
            return [
                'id' => $q['id'],
                'project_name' => $q['project_name'],
                'project_code' => $q['project_code'],
                'region_id' => $region->id,
                'address' => $q['address'],
                'status' => ucwords(strtolower($q['status'])),
                'start_date' => $start_date,
                'end_date' =>  $end_date,
                'date_salary' => 1
            ];
        });
        foreach ($regions->chunk(50) as $chunk) {
            \DB::table('ms_projects')->insert($chunk->toArray());
        }
    }
}
