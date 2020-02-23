<html>
	<head>
		<title>JavaScript/CSS3 Departure Board</title>
		<link rel="stylesheet" href="/booking-system/css/board.css" />
	</head>

	<body style="background-color: #111; font-size: 22pt;">
		<div id="test" style="top: 50px; left: 50%; position: absolute; margin-left: -527px;"></div>

        <script src="/booking-system/js/board.js"></script>
        <script src="https://momentjs.com/downloads/moment.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js" integrity="sha256-T/f7Sju1ZfNNfBh7skWn0idlCBcI3RwdLSS4/I7NQKQ=" crossorigin="anonymous"></script>
		<script>

			var board = new DepartureBoard (document.getElementById ('test'), { rowCount: 14, letterCount: 30 });

            let translations = new Map();
            translations.set("available", "offen");
            translations.set("booked", "gebucht");
            translations.set("boarding", "boarding");
            translations.set("departed", "gestartet");
            translations.set("landed", "gelandet");

            setInterval(() => {
            axios.get('/booking-system/api/slots')
                .then((response) => {
                    let slots = response.data;
                    let lines = [
                        moment().format("DD.MM.YYYY")+"               "+moment().format("HH:mm"),
                        "",
                        "FLUG NR   STATUS      BOARDING",
                        "-------------------------------",
                    ];

                    for(let i = 0; i < slots.length; i++) {
                        let line = ""

                        line += slots[i].flight_number.padEnd(10, " ");
                        line += translations.get(slots[i].status).padEnd(12, " ");
                        if (slots[i].status === "available" || slots[i].status === "booked" || slots[i].status === "boarding") {
                            line += moment(slots[i].starts_on).subtract(15, "minutes").format("HH:mm");
                        } else {
                            line += "--:--";
                        }
                        lines.push(line);
                    }

                    board.setValue(lines);
                });
            }, 2000);

		</script>
	</body>
</html>