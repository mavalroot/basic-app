var pdf = new jsPDF('p','pt','a4');

$(document).ready(function() {
    $('.guardar-qr').on('click', function(e) {
        e.preventDefault();
        let nombre = $(this).data('name');
        guardarQR(nombre);
    });
});

function guardarQR(nombre){
    var pdf = new jsPDF('p','pt','a4');
    pdf.internal.scaleFactor = 2;
    pdf.addHTML($('#printable-qrcode').first(), 20, 20, function() {
    pdf.save('QR_' +  nombre + '.pdf');
});
}
