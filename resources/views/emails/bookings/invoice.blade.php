@component('mail::message')
# Rechnung

Rechnungs-Nr.: 123<br>
Rechnungsdatum: 04.01.2020<br>
Lieferdatum: 21.02.2020

Für Ihren Flug am **{{\Carbon\Carbon::parse($booking->slot->starts_on)->setTimezone('Europe/Berlin')->format('d.m.Y')}}** um **{{\Carbon\Carbon::parse($booking->slot->starts_on)->setTimezone('Europe/Berlin')->format('H:i')}}** Uhr berechnen wir Ihnen folgende Leistungen:

@component('mail::table')
| Bezeichnung       | Menge | MwSt.         | Preis  | Gesamt  |
| ------------- |-------------:|----:| --------:| --------:|
| Rundflug (regulär) | 2 Stk. | 19%     | 100,- € | 200,- €      |
| Rundflug (ermäßigt) | 3 Stk. | 19%      | 50,- € | 150,- €      |
| <td colspan=3 align=right>Summe Netto | 283,50 €      |
| <td colspan=3 align=right>MwSt. 19% | 66,50 €      |
| <td colspan=3 align=right>**Gesamt** | **350,- €**      |
@endcomponent

Freundliche Grüße,<br>
Ihr Frankfurter Verein für Luftfahrt von 1908 e.V.
@endcomponent
