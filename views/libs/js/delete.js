$(document).ready(function() {
    $(document).on('click', '.delete-object', function(){

        var id = $(this).attr('delete-id'), borrado;

        let confirmar = confirm('¿Seguro que deseas eliminar esta entrada?');

        if(confirmar){
            $.post('delete.php', {
                object_id: id
            }, function(data){
                $('#alert-delete').remove();
                $('#table-result').before('<div id="alert-delete" class="alert alert-warning">' + data + '</div>');
                $('#table-result').load(location.href+'` #table-result>*',"");
                $('ul.pagination').load(location.href+'` ul.pagination>*',"");
            });
        }
    });
});
