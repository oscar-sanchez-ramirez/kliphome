var token = $('meta[name="csrf-token"]').attr('content');

$(document).on('click', '#fixermanModal', function(){
    //Getting id of current fixerman
    fixerman_id = $(this).attr('data-id');
    //Listing the fixerman_id detail
    listFixerManDetail(fixerman_id);
});
$(document).on('click', '#fixermanModalImage', function(){
    //Getting id of current fixerman
    fixerman_avatar = $(this).attr('data-id');
    //Listing the fixerman_id detail
    document.getElementById('idFixerman').value = $(this).attr('data-user');
    document.getElementById("imageFixerman").src = fixerman_avatar;
});

function listFixerManDetail(fixerman_id){
    //Listing fixerman detail by id
    var url = window.location.origin+"/tecnicos/detalle/"+fixerman_id;
    $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            $("#fixerManCategories").html('');
            $("#fixerManDelegation").html('');
            for (let index = 0; index < data["delegations"].length; index++) {
                $("#fixerManDelegation").append('<li>'+data["delegations"][index]["title"]+'</li>');
            }
            for (let index = 0; index < data["categories"].length; index++) {
                $("#fixerManCategories").append('<li>'+data["categories"][index]['title']+'</li>');
            }

        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
}

function aproveFixerMan(fixerman_id,name){
    var r = confirm("¿Confirmar registro a "+name+"?");
    if (r == true) {
        var url = window.location.origin+"/tecnicos/aprove";
        $.ajax({
            type: "GET",
            url: url,
            data: { 'fixerman_id': fixerman_id,'_token': token },
            success: function(data) {
                //Listing all fixerman
                $("#state"+fixerman_id).html('<span class="badge badge-success">Validado</span>');
            },
            error: function(data) {
                console.log(data);
                alert("Error al aprobar registro, Porfavor intente de nuevo");
            }
        });
    } else {
    txt = "You pressed Cancel!";
    }
}