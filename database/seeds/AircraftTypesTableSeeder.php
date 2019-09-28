<?php

use Illuminate\Database\Seeder;
use App\AircraftType;

class AircraftTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(__DIR__.'/aircraft_types.json');
        $items = json_decode($json, 'true');
        foreach($items as $item) {
            $aircraftType = new AircraftType();

            $aircraftType->manufacturer = $item['ManufacturerCode'];
            $aircraftType->model = $item['ModelFullName'];
            $aircraftType->designator = $item['Designator'];
            $aircraftType->description = $item['AircraftDescription'];
            $aircraftType->engine_count = $item['EngineCount'];
            $aircraftType->engine_type = $item['EngineType'];
            $aircraftType->wake_turbulence_category = $item['WTC'];

            $aircraftType->save();
        }
    }
}
