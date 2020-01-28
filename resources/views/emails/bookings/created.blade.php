@component('mail::message')
# Ihre Flugbuchung

Vielen Dank für Ihre Buchung. Bald geht es los! Um perfekt für Ihren Flug vorbereitet zu sein, können Sie bereits jetzt unsere Checkliste öffnen.

@component('mail::button', ['url' => 'https://www.fvl-online.de/de/'])
Checkliste für den Flug öffnen
@endcomponent

@component('mail::panel')
**Wichtig:**<br>
@if (\Carbon\Carbon::parse($booking->slot->starts_on)->isToday())
Ihr Flug startet heute um **{{\Carbon\Carbon::parse($booking->slot->starts_on)->setTimezone('Europe/Berlin')->format('H:i')}}** Uhr.<br>
@else
Ihr Flug startet am **{{\Carbon\Carbon::parse($booking->slot->starts_on)->setTimezone('Europe/Berlin')->format('d.m.Y')}}** um **{{\Carbon\Carbon::parse($booking->slot->starts_on)->setTimezone('Europe/Berlin')->format('H:i')}}** Uhr.<br>
@endif
Bitte finden Sie sich **15 vorher im Boarding-Bereich** ein.
@endcomponent

@component('mail::button', ['url' => 'https://goo.gl/maps/4sMxsAo9c1pLQFAV9'])
Wo ist der Boarding-Bereich?
@endcomponent

Die Rechnung erhalten Sie in einer separaten E-Mail.

Wir freuen uns auf einen angenehmen Flug mit Ihnen!

Freundliche Grüße,<br>
Ihr Frankfurter Verein für Luftfahrt von 1908 e.V.
@endcomponent
