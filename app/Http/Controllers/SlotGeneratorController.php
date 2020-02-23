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
                    'flight_number' => 'FVL'.$now->setTimezone('UTC')->format('G').$counter,
                    'starts_at' => clone $now,
                    'ends_at' => clone $now->addMinutes($data['slot_duration']) ,
                    'pilot_id' => ''
                ];
                $table[$aircraft]['slots'][] = $record;
                $table[$aircraft]['aircraft'] = [
                    "id" => $aircraftMap[$aircraft]->id,
                    "name" => $aircraftMap[$aircraft]->callsign." - ".$aircraftMap[$aircraft]->aircraftType->designator." (".$aircraftMap[$aircraft]->load."kg)"
                ];

                $now->addMinutes($data['idle_time']);
                $counter = ($counter + 17) % 100;
            }

            $i++;
        }

        $table = array_values($table);

        $pilots = User::all();

        return view('slot_generator/step_2', ['title' => 'Slot', 'table' => $table, 'pilots' => $pilots]);
    }

    public function storeStep2(Request $request)
    {
        $validatedData = $request->validate([
            'slots' => 'required|array'
        ]);

        $slots = array_values($validatedData['slots']);

        foreach($slots as $slot) {
            if (!isset($slot['pilot_id']) or $slot['pilot_id'] == "" or $slot['pilot_id'] == "suspend") {
                continue;
            }

            $slotTmp = new Slot();
            $slotTmp->starts_on = Carbon::parse($slot['starts_at'])->toArray()['formatted'];
            $slotTmp->ends_on = Carbon::parse($slot['ends_at'])->toArray()['formatted'];
            $slotTmp->aircraft_id = $slot['aircraft_id'];
            $slotTmp->pilot_id = $slot['pilot_id'];
            $slotTmp->flight_number = $slot['flight_number'];
            $slotTmp->save();
        }

        #$request->session()->forget('slot_generator');

        return redirect()->action('SlotController@index');
    }
}
