$(document).ready(function(){
    $('.data-table').DataTable({
        "ajax": {
            "url":'https://pagalofacil.com/services/ServicioAdmin.php',
            "type":"POST",
            "data":{'accion':'listarTransacciones'}
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            var id = $(nRow).find('td:first').html();
            $(nRow).find('td:last').html(
                "<div style='text-align: center;'>"+
                "<a onclick='$(\"#manage_modal\").modal(\"open\");$(\"#id_transaccion\").val("+id+")' data-id='"+id+"' class='btn-floating waves-effect waves-light blue'> " +
                        "<i class='mdi-editor-mode-edit tooltipped' data-position='top' data-delay='50' data-tooltip='Gestionar'></i>"+
                    "</a>&nbsp;"+
                    "&nbsp;<a onclick='listarGestiones("+id+")' data-id='"+id+"' class='btn-floating waves-effect waves-light orange'> " +
                        "<i class='mdi-action-history tooltipped' data-position='top' data-delay='50' data-tooltip='Historial'></i>"+
                    "</a>"+
                "</div>"
            );
        },
        "fnDrawCallback": function () {
            $('.tooltipped').tooltip({delay: 50});
        }
    });
});