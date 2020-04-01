var token = $('meta[name="csrf-token"]').attr('content');

$(document).on('click', '#fixermanModalButton', function(){
    //Listing the fixerman_id detail
    id_orden = $(this).attr('data-id');
    listFixerManDetail(id_orden);
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
                $(".tbodyModal").append(' <tr><td>'+data['fixerman'][index]["name"]+'</td><td>'+data["fixerman"][index]["phone"]+'</td><td>'+categories+'</td><td><form action="'+window.location.origin+'/tecnicos/asignarTecnico/'+data["fixerman"][index]["id"]+'/'+id_orden+'" method="POST"><input type="hidden" name="_token" value="'+token+'"><button class="au-btn au-btn-icon au-btn--green au-btn--small" type="submit" id="fixermanModalButton" title="Asignar">Asignar</button></form></td></tr>');
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