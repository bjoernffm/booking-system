<body style="margin: 0; padding: 0;">
<script src="/booking-system/js/pdfkit.js"></script>
<script src="https://github.com/devongovett/blob-stream/releases/download/v0.1.3/blob-stream.js"></script>
<iframe id="iframe" src="" width="100%" height="100%" style="border: 0;"> </iframe>
<script>
    // create a document and pipe to a blob
var doc = new PDFDocument({size: [595.28, 219.53]});
var stream = doc.pipe(blobStream());

// draw some text

let passengers = {!! json_encode($passengers) !!};

for(let i = 0; i < passengers.length; i++) {
    if (i > 0) {
        doc.addPage();
    }
    //doc.fontSize(25).text("{{ count($passengers) }} PAX", 100, 80);
    doc.fontSize(25).text("QEF to QEF", 100, 30);
    doc.fontSize(25).text(passengers[i].name, 100, 80);
    doc.fontSize(10).text("{{ $booking->id }}", 100, 110);

    doc.rotate(90, {origin: [100, 100]});
    doc.image("{{$pdf317}}", 0, 130, {width: 220});

    doc.rotate(-90, {origin: [100, 100]});
}


// end and display the document in the iframe to the right
doc.end();
stream.on('finish', function() {
    iframe.src = stream.toBlobURL('application/pdf');
    iframe.onload = function() {
        iframe.focus();
        iframe.contentWindow.print();
    }
});
</script>
</body>