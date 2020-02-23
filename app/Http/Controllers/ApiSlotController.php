<?php

namespace App\Http\Controllers;

use App\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiSlotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slots = DB::table('slots')
            ->leftJoin('aircrafts', 'slots.aircraft_id', '=', 'aircrafts.id')
            ->leftJoin('users', 'slots.pilot_id', '=', 'users.id')
            ->leftJoin('aircraft_types', 'aircrafts.type', '=', 'aircraft_types.id')
            ->select(
                'slots.*',
                'users.id as pilot_id',
                'users.firstname as pilot_firstname',
                'users.lastname as pilot_lastname',
                'aircrafts.id as aircraft_id',
                'aircrafts.callsign as aircraft_callsign',
                'aircrafts.load as aircraft_load',
                'aircraft_types.designator as aircraft_designator'
            )->orderBy('starts_on', 'asc')
            ->whereRaw('(slots.status != "landed") OR (slots.status = "landed" AND slots.updated_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE))')
            ->whereNull('slots.deleted_at')
            ->get();

        $returnBuffer = [];
        $i = 48;
        foreach($slots as $slot) {
            $returnBuffer[] = [
                'id' => $slot->id,
                'flight_number' => $slot->flight_number,
                'status' => $slot->status,
                'boarding_on' => date('Y-m-d').'T'.explode(' ', $slot->starts_on)[1].'Z',
                'starts_on' => date('Y-m-d').'T'.explode(' ', $slot->starts_on)[1].'Z',
                'ends_on' => date('Y-m-d').'T'.explode(' ', $slot->ends_on)[1].'Z',
                'aircraft' => [
                    'id' => $slot->aircraft_id,
                    'callsign' => $slot->aircraft_callsign,
                    'load' => $slot->aircraft_load,
                    'designator' => $slot->aircraft_designator,
                ],
                'pilot' => [
                    'id' => $slot->pilot_id,
                    'firstname' => $slot->pilot_firstname,
                    'lastname' => $slot->pilot_firstname,
                ],
            ];
        }

        #array_shift($returnBuffer);
        #array_shift($returnBuffer);

        return $returnBuffer;

        return response()->json([
        [
    "callsign" => "DEDRP (190kg)",
    "slots" => [
        [
            "id" => "1",
            "status" => "landed",
            "starts_on" => date('Y-m-d')."T16:50:00Z",
            "ends_on" => date('Y-m-d')."T17:10:00Z"
        ],
        [
            "id" => "2",
            "status" => "departed",
            "starts_on" => date('Y-m-d')."T17:20:00Z",
            "ends_on" => date('Y-m-d')."T17:40:00Z"
        ],
        [
            "id" => "3",
            "status" => "boarding",
            "starts_on" => date('Y-m-d')."T17:50:00Z",
            "ends_on" => date('Y-m-d')."T18:10:00Z"
        ],
        [
            "id" => "4",
            "status" => "booked",
            "starts_on" => date('Y-m-d')."T18:20:00Z",
            "ends_on" => date('Y-m-d')."T18:40:00Z"
        ],
        [
            "id" => "5",
            "status" => "available",
            "starts_on" => date('Y-m-d')."T18:50:00Z",
            "ends_on" => date('Y-m-d')."T19:10:00Z"
        ]
    ]
], [
    "callsign" => "DELZC (220kg)",
    "slots" => [
        [
            "id" => "0808e315-ffa4-4ef0-8808-0c6160302043",
            "status" => "landed",
            "starts_on" => date('Y-m-d')."T12:00:00Z",
            "ends_on" => date('Y-m-d')."T12:20:00Z"
        ],
        [
            "id" => "0808e315-ffa4-4ef0-8808-0c6160302043",
            "status" => "departed",
            "starts_on" => date('Y-m-d')."T12:30:00Z",
            "ends_on" => date('Y-m-d')."T12:50:00Z"
        ],
        [
            "id" => "0808e315-ffa4-4ef0-8808-0c6160302043",
            "status" => "boarding",
            "starts_on" => date('Y-m-d')."T13:00:00Z",
            "ends_on" => date('Y-m-d')."T13:20:00Z"
        ],
        [
            "id" => "0808e315-ffa4-4ef0-8808-0c6160302043",
            "status" => "booked",
            "starts_on" => date('Y-m-d')."T13:30:00Z",
            "ends_on" => date('Y-m-d')."T13:50:00Z"
        ],
        [
            "id" => "0808e315-ffa4-4ef0-8808-0c6160302043",
            "status" => "available",
            "starts_on" => date('Y-m-d')."T14:00:00Z",
            "ends_on" => date('Y-m-d')."T14:20:00Z"
        ]
    ]
], [
    "callsign" => "DEFVC (180kg)",
    "slots" => [
        [
            "id" => "1",
            "status" => "landed",
            "starts_on" => date('Y-m-d')."T17:10:00Z",
            "ends_on" => date('Y-m-d')."T17:30:00Z"
        ],
        [
            "id" => "2",
            "status" => "departed",
            "starts_on" => date('Y-m-d')."T17:40:00Z",
            "ends_on" => date('Y-m-d')."T18:00:00Z"
        ],
        [
            "id" => "3",
            "status" => "boarding",
            "starts_on" => date('Y-m-d')."T18:10:00Z",
            "ends_on" => date('Y-m-d')."T18:30:00Z"
        ],
        [
            "id" => "4",
            "status" => "booked",
            "starts_on" => date('Y-m-d')."T18:40:00Z",
            "ends_on" => date('Y-m-d')."T19:00:00Z"
        ],
        [
            "id" => "5",
            "status" => "available",
            "starts_on" => date('Y-m-d')."T19:10:00Z",
            "ends_on" => date('Y-m-d')."T19:30:00Z"
        ]
    ]
]

]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function show(Slot $slot)
    {
        return response()->json($slot);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slot $slot)
    {
        $validatedData = $request->validate([
            'status' => 'in:available,booked,boarding,departed,landed|required'
        ]);

        $slot->status = $validatedData['status'];
        $slot->save();

        return response()->json($slot);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slot $slot)
    {
        //
    }
}
