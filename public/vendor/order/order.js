var token = $('meta[name="csrf-token"]').attr('content');

$(document).on('click', '#fixermanModalButton', function(){
    //Listing the fixerman_id detail
    id_orden = $(this).attr('data-id');
    listFixerManDetail(id_orden);
});
$( "#filter_orders" ).change(function() {
    let key = $("#filter_orders").val();
    var url = window.location.origin+"/ordenes?filtro="+key;
    window.location.href = url;
  });

function listFixerManDetail(id_orden){

    // Listing fixerman detail by id
    var url = window.location.origin+"/tecnicos/listado";
    $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            console.log(data);
            $(".tbodyModal").html('');
            for (let index = 0; index < data["fixerman"].length; index++) {
                let categories = '';
                for (let indexJ = 0; indexJ < data["fixerman"][index]["categories"].length; indexJ++) {
                    let category_id = data["fixerman"][index]["categories"][indexJ]["category_id"];
                    const category = data["categories"].find( x => x.id === category_id );
                    categories = categories+' '+category.title;
                }
                $(".tbodyModal").append(' <tr><td>'+data['fixerman'][index]["name"]+' '+data['fixerman'][index]["lastName"]+'</td><td>'+data["fixerman"][index]["phone"]+'</td><td>'+categories+'</td><td><form action="'+window.location.origin+'/tecnicos/asignarTecnico/'+data["fixerman"][index]["id"]+'/'+id_orden+'" method="POST"><input type="hidden" name="_token" value="'+token+'"><button class="au-btn au-btn-icon au-btn--green au-btn--small" type="submit" id="fixermanModalButton" title="Asignar">Asignar</button></form></td></tr>');
                categories = "";
            }
        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
}
function esCereza(fruta) {
    return fruta.nombre === 'cerezas';
}

function export_excel(key){
    var url = window.location.origin+"/ordenes/export?filtro="+key;
    window.location.href = url;
}

$(document).on('keypress', '#search_cliente_orden', function(){
    var url = window.location.origin+"/ordenes/busqueda";
    var key = $("#search_cliente_orden").val();
    console.log(key);
    $.ajax({
        type: "GET",
        data: { 'keywords': key,'_token': token },
        url: url,
        success: function(data) {
            var urlorigin = window.location.origin;
            console.log(data);
            $(".tabla_order").html('');
            for (let index = 0; index < data["orders"].length; index++) {
                var boton = '';
                if(data['orders'][index]['state'] == 'CANCELLED'){
                    boton = '<div class="table-data-feature"><span class="status--denied">Cancelado</span></div>';
                }else{
                    boton = '<div class="table-data-feature"><a class="au-btn au-btn--green" data-toggle="tooltip" data-placement="top" title="Ver" href="'+urlorigin+'/ordenes/detalle-orden/'+data["orders"][index]["id"]+'">Revisar</a></div>';
                }
                $(".tabla_order").append(' <tr><td>'+data['orders'][index]["name"]+' '+data['orders'][index]["lastName"]+'</td><td>'+data["orders"][index]["categoria"]+'</td><td>'+data["orders"][index]["created_at"]+'</td><td>'+data["orders"][index]["stateicon"]+'</td><td>'+boton+'</td></tr>');
            }
        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
});
