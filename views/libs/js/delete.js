$(document).ready(function() {
    $(document).on('click', '.delete-object', function(){

        var id = $(this).attr('delete-id'), borrado;

        let confirmar = confirm('¿Seguro que deseas eliminar esta entrada?');

        if(confirmar){
            $.post('delete.php', {
                object_id: id
            }, function(data){
                alert(data);
                location.reload();
            });
        }
    });
});
