<?php

namespace App\Http\Controllers;

use App\Aircraft;
use App\User;
use App\Slot;
use Illuminate\Http\Request;

use Carbon\Carbon;

class SlotGeneratorController extends Controller
{
    public function step1()
    {
        $aircrafts = Aircraft::all();
        return view('slot_generator/step_1', ['title' => 'Slot', 'aircrafts' => $aircrafts]);
    }

    public function storeStep1(Request $request)
    {
        $validatedData = $request->validate([
            'starts_on' => 'required|date',
            'ends_on' => 'required|date',
            'slot_duration' => 'numeric|min:1|nullable',
            'idle_time' => 'numeric|min:0|nullable',
            'offset' => 'numeric|min:0|nullable',
            'aircrafts' => 'required|array'
        ]);

        if ($validatedData['slot_duration'] === null) {
            $validatedData['slot_duration'] = 20;
        }
        if ($validatedData['idle_time'] === null) {
            $validatedData['idle_time'] = 25;
        }
        if ($validatedData['offset'] === null) {
            $validatedData['offset'] = 5;
        }

        session(['slot_generator' => $validatedData]);

        return redirect()->action('SlotGeneratorController@step2');
    }

    public function step2()
    {
        $data = $value = session('slot_generator');

        $aircrafts = Aircraft::all();
        $aircraftMap = [];
        foreach($aircrafts as $aircraft) {
            $aircraftMap[$aircraft->id] = $aircraft;
        }

        $table = [];

        $end = new Carbon($data['ends_on'], 'Europe/Berlin');

        $i = 0;
        $counter = 3;
        foreach($data['aircrafts'] as $aircraft) {
            $now = new Carbon($data['starts_on'], 'Europe/Berlin');
            $now->addMinutes($i * $data['offset']);

            while($now < $end) {
                $record = [
                    'aircraft' => $aircraft,
                    'aircraftObject' => $aircraftMap[$aircraft],
                    'flight_number' => 'FVL'.$now->setTimezone('UTC')->format('G').$counter,
                    'starts_at' => clone $now,
                    'ends_at' => clone $now->addMinutes($data['slot_duration'])
                ];
                $table[$i][] = $record;

                $slot = new Slot();
                $slot->flight_number = $record['flight_number'];
                $slot->starts_on = $record['starts_at']->setTimezone('UTC')->format('Y-m-d H:i:s');
                $slot->ends_on = $record['ends_at']->setTimezone('UTC')->format('Y-m-d H:i:s');
                $slot->aircraft_id = $record['aircraft'];
                $slot->pilot_id = '207089b7-5086-4c60-ac01-dc4b7de7a477';
                #$slot->save();

                $now->addMinutes($data['idle_time']);
                $counter = ($counter + 17) % 100;
            }

            $i++;
        }

        $newTable = [];
        foreach($table as $colKey => $rows) {
            foreach($rows as $rowKey => $row) {
                $newTable[$rowKey][$colKey] = $row;
            }
        }

        $pilots = User::all();

        return view('slot_generator/step_2', ['title' => 'Slot', 'table' => $newTable, 'pilots' => $pilots]);
    }
}
