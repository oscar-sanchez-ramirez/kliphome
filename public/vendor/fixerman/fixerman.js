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
    console.log(fixerman_id);
    document.getElementById("acuerdo_laboral").checked = false;
    document.getElementById("prueba_psicologica").checked = false;
    document.getElementById("comprobante_domicilio").checked = false;
    document.getElementById("asistencia_entrevista").checked = false;
    document.getElementById("copia_dni").checked = false;
    document.getElementById("foto").checked = false;
    document.getElementById("kit_bienvenida").checked = false;
    document.getElementById('idFixerman').value = fixerman_id;
    //Listing fixerman detail by id
    var url = window.location.origin+"/tecnicos/detalle/"+fixerman_id;
    $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            console.log(data);
            if(data["ficha"]["acuerdo_laboral"] == "S"){
                document.getElementById("acuerdo_laboral").checked = true;
            }
            if(data["ficha"]["prueba_psicologica"] == "S"){
                document.getElementById("prueba_psicologica").checked = true;
            }
            if(data["ficha"]["comprobante_domicilio"] == "S"){
                document.getElementById("comprobante_domicilio").checked = true;
            }
            if(data["ficha"]["asistencia_entrevista"] == "S"){
                document.getElementById("asistencia_entrevista").checked = true;
            }
            if(data["ficha"]["copia_dni"] == "S"){
                document.getElementById("copia_dni").checked = true;
            }
            if(data["ficha"]["foto"] == "S"){
                document.getElementById("foto").checked = true;
            }
            if(data["ficha"]["kit_bienvenida"] == "S"){
                document.getElementById("kit_bienvenida").checked = true;
            }
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

function guardar_ficha(){
    let id_fixerman = document.getElementById('idFixerman').checked;
    let acuerdo_laboral = document.getElementById("acuerdo_laboral").checked;
    let prueba_psicologica = document.getElementById("prueba_psicologica").checked;
    let comprobante_domicilio = document.getElementById("comprobante_domicilio").checked;
    let asistencia_entrevista = document.getElementById("asistencia_entrevista").checked;
    let copia_dni = document.getElementById("copia_dni").checked;
    let foto = document.getElementById("foto").checked;
    let kit_bienvenida = document.getElementById("kit_bienvenida").checked;
    var url = url = window.location.origin+"/tecnicos/guardar_ficha";
    $.ajax({
        type: "POST",
        url: url,
        data: { 'fixerman_id': id_fixerman,'_token': token,'acuerdo_laboral':acuerdo_laboral,'prueba_psicologica':prueba_psicologica,'comprobante_domicilio':comprobante_domicilio,'asistencia_entrevista':asistencia_entrevista,'copia_dni':copia_dni,'foto':foto,'kit_bienvenida':kit_bienvenida },
        success: function(data) {
            alert("Datos actulizados");
        },
        error: function(data) {
            alert("Error al aprobar registro, Porfavor intente de nuevo");
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