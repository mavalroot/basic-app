var pdf = new jsPDF('p','pt','a4');

$(document).ready(function() {
    $('.guardar-qr').on('click', function(e) {
        e.preventDefault();
        HtmlToImage();
    });
});

function HtmlToImage(){
    var pdf = new jsPDF('p','pt','a4');
    pdf.internal.scaleFactor = 2;
    pdf.addHTML($('#printable-qrcode').first(), 20, 20, function() {
    pdf.save('web.pdf');
});
}
