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

        $.get( url, $( this ).serialize(), function(data) {
            $('body').append(`<div id="option-window"><div id="window"><div id="close"><i class="fas fa-times"></i></div>${data}</div></div>`);
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

function cambiarUsuario() {
    $('form[name="cambiar-usuario"]').on('submit', function (e) {
        e.preventDefault();
        let input = $('input[list="cambiar"]');
        let value = input.val();
        let usuario = $('option[value="' + value + '"]').data('id');
        let aparato = $(this).find('input[type="hidden"]').val();

        $.post( 'ajax/cambiarUsuario.php', {usuario_id: usuario, id: aparato}, function(data) {
            if (usuario != undefined) {
                $('#option-window > #window > #close').trigger('click');
                $('#row_' + aparato).load(location.href+` #row_${aparato}>*`,"");
            } else {
                input.addClass('is-invalid');
            }
        });
    });
}
