<body style="margin: 0; padding: 0;">
<script src="/booking-system/js/pdfkit.js"></script>
<script src="https://github.com/devongovett/blob-stream/releases/download/v0.1.3/blob-stream.js"></script>
<iframe id="iframe" src="" width="100%" height="100%" style="border: 0;"> </iframe>
<script>
    // create a document and pipe to a blob
var doc = new PDFDocument({size: [595.27584, 283.46472]});
var stream = doc.pipe(blobStream());

// draw some text

let passengers = {!! json_encode($passengers) !!};

for(let i = 0; i < passengers.length; i++) {
    if (i > 0) {
        doc.addPage();
    }
    //doc.fontSize(25).text("{{ count($passengers) }} PAX", 100, 80);
    doc.fontSize(25).text("Tag der offenen Tür 2020", 110, 30);
    doc.fontSize(25).text("{{ $booking->slot->flight_number }} - QFE nach QFE", 110, 80);
    doc.fontSize(25).text(passengers[i].name, 110, 110);
    doc.fontSize(10).text("{{ $booking->id }}", 110, 140);

    doc.rotate(90);
    doc.image("{{$pdf317}}", 20, -95, {width: 250});

    let disclaimer = `Hiermit bestätige ich, `+passengers[i].name+`, dass Lorem ipsum dolor sit amet consectetuer dictumst sagittis eu Nulla nibh. Wisi Nulla Lorem et ac dolor feugiat ante nibh interdum tempus. Nec lobortis consequat turpis vitae ipsum sit laoreet orci sem sed.\n\nEgelsbach der 08.02.2020 ______________________________`;
    doc.rotate(-90);

    doc.rotate(-90,{ origin: [400, -150]});
    doc.fontSize(8).text(disclaimer, -20, -20, {width: 250});
    doc.rotate(90,{ origin: [400, -150]});

    doc.moveTo(520, 0).lineTo(520,300).dash(5).stroke();
}


// end and display the document in the iframe to the right
doc.end();
stream.on('finish', function() {
    iframe.src = stream.toBlobURL('application/pdf');
    iframe.onload = function() {
        iframe.focus();
        //iframe.contentWindow.print();
    }
});
</script>
</body>