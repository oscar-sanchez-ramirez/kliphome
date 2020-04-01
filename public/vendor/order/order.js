var token = $('meta[name="csrf-token"]').attr('content');

$(document).on('click', '#fixermanModalButton', function(){
    //Listing the fixerman_id detail
    listFixerManDetail();
});

function listFixerManDetail(){

    // Listing fixerman detail by id
    var url = window.location.origin+"/tecnicos/listado";
    $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            console.log(data);
            $(".tbodyModal").html('');
            for (let index = 0; index < data.length; index++) {
                $(".tbodyModal").append(' <tr><td>'+data[index]["name"]+'</td><td>'+data[index]["phone"]+'</td><td></td>'+data[index]["phone"]+'<td></td></tr>');
            }
        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
}