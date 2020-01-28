<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <title>{{ $booking->aircraft_callsign }} | {{ \Carbon\Carbon::parse($booking->starts_on)->setTimezone('Europe/Berlin')->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->ends_on)->setTimezone('Europe/Berlin')->format('H:i') }} lcl</title>
    </head>
    <body>
        <div class="container-fluid">
            <br />
            @if ($booking->internal_information !== null)
                <div class="alert alert-info" role="alert">
                  <b>Internal Information:</b><br />
                  {{ $booking->internal_information }}
                </div>
            @endif
            @if ($booking->small_headsets == 1)
                <div class="alert alert-warning" role="alert">
                    <b>1 small Headset</b> needed
                </div>
            @elseif ($booking->small_headsets > 1)
                <div class="alert alert-warning" role="alert">
                    <b>{{ $booking->small_headsets }} small Headsets</b> needed
                </div>
            @endif
            <dl class="row">
                <dt class="col-sm-3">SLOT</dt>
                <dd class="col-sm-9">
                    {{ $booking->aircraft_callsign }} / {{ $booking->aircraft_designator }} / {{ $booking->pilot_firstname }} {{ $booking->pilot_lastname }}<br />
                    {{ \Carbon\Carbon::parse($booking->starts_on)->format('d.m.Y') }} {{ \Carbon\Carbon::parse($booking->starts_on)->setTimezone('Europe/Berlin')->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->ends_on)->setTimezone('Europe/Berlin')->format('H:i') }} lcl
                </dd>
                <dt class="col-sm-3">STATUS</dt>
                <dd class="col-sm-9">
                    @if ($booking->status == 'available')
                        <span class="badge badge-pill badge-success">Available</span>
                    @elseif ($booking->status == 'booked')
                        <span class="badge badge-pill badge-info">Booked</span>
                    @elseif ($booking->status == 'boarding')
                        <span class="badge badge-pill badge-warning">Boarding</span>
                    @elseif ($booking->status == 'departed')
                        <span class="badge badge-pill badge-info">Departed</span>
                    @elseif ($booking->status == 'landed')
                        <span class="badge badge-pill badge-secondary">Landed</span>
                    @endif
                </dd>
                <dt class="col-sm-3">PAX</dt>
                <dd class="col-sm-9">
                    @if(count($booking->passengers) == 1)
                         1 Passenger
                    @else
                         {{ count($booking->passengers) }} Passengers
                    @endif
                    <ul>
                        @foreach ($booking->passengers as $passenger)
                            <li>
                                {{ $passenger->firstname }} {{ $passenger->lastname }}
                                @if ($passenger->infoText != '')
                                    ({{ $passenger->infoText }})
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </dd>
            </dl>
            <a href="#" class="btn btn-success btn-lg btn-block" style="padding: 15px 0;">Start Boarding</a>
            <hr />
            @if ($booking->pilot_mobile != null)
                <a href="tel:{{ $booking->pilot_mobile }}" class="btn btn-primary btn-lg btn-block" style="padding: 15px 0;">Contact Pilot</a>
            @endif
            <a href="tel:+491774147290" class="btn btn-info btn-lg btn-block" style="padding: 15px 0;">Contact Booking</a>

            @if ($booking->mobile !== null)
                <a href="tel:{{ $booking->mobile }}" class="btn btn-dark btn-lg btn-block" style="padding: 15px 0;">Contact Pax</a>
            @endif
        </div>
    </body>
</html>
