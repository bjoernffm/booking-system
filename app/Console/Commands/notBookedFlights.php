<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Slot;
use Carbon\Carbon;

class notBookedFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slots:tidyup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes not booked flights';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // tidy up unbooked flights
        $slots = Slot::where('status', 'available')
                    ->where('starts_on', '<=', Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i'))
                    ->orderBy('starts_on')
                   ->get();

        foreach($slots as $slot) {
            $slot->status = 'landed';
            $slot->save();
        }

        // depart boarded flights
        $slots = Slot::where('status', 'boarding')
                    ->where('starts_on', '<=', Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i'))
                    ->orderBy('starts_on')
                   ->get();

        foreach($slots as $slot) {
            $slot->status = 'departed';
            $slot->save();
        }

        // land departed flights
        $slots = Slot::where('status', 'departed')
                    ->where('ends_on', '<=', Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i'))
                    ->orderBy('ends_on')
                   ->get();

        foreach($slots as $slot) {
            $slot->status = 'landed';
            $slot->save();
        }
    }
}
