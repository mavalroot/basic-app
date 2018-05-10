$('body').ready(function () {
    cerrarVentana();
});

/**
 * Hace que aparezca la ventana del formulario.
 * @param  {string} name Nombre del formulario.
 * @param  {string} url  Url a la que se le hace la petición ajax.
 */
function ventana(name, url, bool = true) {
    let ev;
    if (bool) {
        name = `form[name="${name}"]`;
        ev = 'submit';
    } else {
        name = `button[name="${name}"]`;
        ev = 'click';
    }
    $('body').on(ev, name, function (e) {
        e.preventDefault();

        $.post( url, $( this ).serialize(), function(data) {
            $('body').append(data);
        });
    });
}

/**
 * Recupera una fila del controlador y la reempla por la actual.
 * @param  {string} name Nombre del formulario.
 * @param  {string} url  Url a la que se le hace la petición ajax.
 */
function cambiarEstado(name, url) {
    $('body').one('submit', `form[name="${name}"]`, function (e) {
        e.preventDefault();
        let id = $(this).children('input[type="hidden"]').val();

        $.post( url, $( this ).serialize(), function(data) {
            if (!data.includes('<div id="option-window">')) {
                $('#row_'+id).replaceWith(data);
                $('#option-window > #window > #close').trigger('click');
                cambiarEstado(name, url);
            } else {
                $('#option-window').replaceWith(data);
                cambiarEstado(name, url);
            }
        });

    });
}

/**
 * Cierra la ventana del formulario con la x de la esquina.
 */
function cerrarVentana() {
    $('body').on('click', '#option-window > #window > #close', function() {
        $(this).closest('#option-window').remove();
    });
}

function buscarTitular() {
    $('form[name="buscar-titular"]').on('submit', function (e) {
        e.preventDefault();
        let input = $('input[list="buscar"]');
        let value = input.val();
        let titular_id = $('option[value="' + value + '"]').data('id');

        $.post( 'ajax/buscar-titular.php', {id: titular_id}, function(data) {
            if (titular_id != undefined) {
                $('#titular-form').empty();
                $('#titular-form').append(data);
                $('#option-window > #window > #close').trigger('click');
            } else {
                input.addClass('is-invalid');
            }
        });
    });
}
