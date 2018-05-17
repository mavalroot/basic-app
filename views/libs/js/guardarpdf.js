function guardar(nombre) {
    var doc = new jsPDF('p', 'pt');

    let titulo = nombre.replace(/_/g, ' ');
    doc.autoTableSetDefaults({
        addPageContent: function(data) {
            doc.setFontSize(20);
            doc.text(titulo, data.settings.margin.left, 40);
        },
        margin: {top: 60}
    });
  var columns = [
        {title: "Título", dataKey: "title"},
        {title: "Contenido", dataKey: "content"}
    ];
  var data = [];
  let tr = $('#content tr');
  let li = $('#informacion-extra li');
  let h4 = $('#informacion-extra h4').text();
  $.each(tr, function(index, value) {
      data.push({title: value.cells[0].innerText, content: value.cells[1].innerText})
  });

  let contenido = '';
  var length = li.length;
  if (length) {
      $.each(li, function(index, value) {
          contenido += '- ' + value.innerText;
          if (index != (length - 1)) {
             contenido += '\r\n';
          }
      });
      data.push({title: h4, content: contenido})
  }

  tabla(columns, data, doc, {
      title: {fillColor: [41, 128, 185], textColor: 255, fontStyle: 'bold'},
      content: {columnWidth: 'auto'}
  });

  doc.save(nombre + '.pdf');
}

$(document).ready(function() {
    $('#export').on('click', function() {
        let nombre = $(this).data('name');
        guardar(nombre);
    });
});

function tabla(columns, data, doc, columnStyles = false) {
    doc.autoTable(columns, data, {
        border: true,
          showHeader: 'never',
          columnStyles: columnStyles,
          styles: {
              overflow: 'linebreak',
              columnWidth: 'wrap',
              lineWidth: '1',
              lineColor: '0',
              textColor: '0'},
      });
}
