var token = $('meta[name="csrf-token"]').attr('content');

$(document).on('click', '#fixermanModal', function(){
    //Getting id of current fixerman
    fixerman_id = $(this).attr('data-id');
    //Listing the fixerman_id detail
    listFixerManDetail(fixerman_id);
});
$(document).on('click', '#fichatecnica', function(){
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

function listFichaTecnica(fixerman_id){
    console.log(fixerman_id);
    //Listing fixerman detail by id
    var url = window.location.origin+"/tecnicos/ficha_tecnica/"+fixerman_id;
    $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            console.log(data);
            $("#ficha").html('');
            let prueba_psicologica = '<div class="form-check"><input type="checkbox" class="form-check-input" id="prueba_psicologica"><label class="form-check-label" for="exampleCheck1">Prueba Psicologica</label></div>';
            let acuerdo_laboral = '<div class="form-check"><input type="checkbox" class="form-check-input" id="acuerdo_laboral"><label class="form-check-label" for="exampleCheck1">Acuerdo Laboral</label></div>';
            let comprobante_domicilio = '<div class="form-check"><input type="checkbox" class="form-check-input" id="comprobante_domicilio"><label class="form-check-label" for="exampleCheck1">Comprobante de domicilio</label></div>';
            let asistencia_entrevista = '<div class="form-check"><input type="checkbox" class="form-check-input" id="asistencia_entrevista"><label class="form-check-label" for="exampleCheck1">Asistencia a entrevista</label></div>';
            let copia_dni = '<div class="form-check"><input type="checkbox" class="form-check-input" id="copia_dni"><label class="form-check-label" for="exampleCheck1">Copia de identificación oficial</label></div>';
            let foto = '<div class="form-check"><input type="checkbox" class="form-check-input" id="foto"><label class="form-check-label" for="exampleCheck1">Foto</label></div>';
            let kit_bienvenida = '<div class="form-check"><input type="checkbox" class="form-check-input" id="kit_bienvenida"><label class="form-check-label" for="exampleCheck1">Kit de bienvenida</label></div>';
            $("#ficha").append(prueba_psicologica,acuerdo_laboral,comprobante_domicilio,asistencia_entrevista,copia_dni,foto,kit_bienvenida);
            // $("#fixerManDelegation").html('');
            // for (let index = 0; index < data["delegations"].length; index++) {
            //     $("#fixerManDelegation").append('<li>'+data["delegations"][index]["title"]+'</li>');
            // }
            // for (let index = 0; index < data["categories"].length; index++) {
            //     $("#fixerManCategories").append('<li>'+data["categories"][index]['title']+'</li>');
            // }

        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
}

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
                alert("Error al aprobar registro, Porfavor intente de nuevo");
            }
        });
    } else {
    txt = "You pressed Cancel!";
    }
}