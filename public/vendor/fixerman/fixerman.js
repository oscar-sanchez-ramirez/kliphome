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
            for(let index = 0; index < data["reviews"].length; index++){
                let average = (Number(data["reviews"][index]['presentation']) + Number(data["reviews"][index]['problemSolve']) + Number(data["reviews"][index]['puntuality'])) / 3;
                let collapse = '<div class="collapse" id="collapseExample"><div class="card card-body">hola</div></div>';
                let star_average = star_function(average);
                console.log(star_average);
                $("#fixerManReviews").append('<div><a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">'+average+' '+star_average+'</a><li>'+data["reviews"][index]['comment']+'</li>'+collapse+'</div>');
            }

        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
}

function guardar_ficha(){
    let id_fixerman = document.getElementById('idFixerman').value;
    let acuerdo_laboral ;if(document.getElementById("acuerdo_laboral").checked === true){acuerdo_laboral = "S";}else{acuerdo_laboral = "N";}
    let prueba_psicologica ;if(document.getElementById("prueba_psicologica").checked === true){prueba_psicologica = "S";}else{prueba_psicologica = "N";}
    let comprobante_domicilio ;if(document.getElementById("comprobante_domicilio").checked === true){comprobante_domicilio = "S";}else{comprobante_domicilio = "N";}
    let asistencia_entrevista ;if(document.getElementById("asistencia_entrevista").checked === true){asistencia_entrevista = "S";}else{asistencia_entrevista = "N";}
    let copia_dni ;if(document.getElementById("copia_dni").checked === true){copia_dni = "S";}else{copia_dni = "N";}
    let foto ;if(document.getElementById("foto").checked === true){foto = "S";}else{foto = "N";}
    let kit_bienvenida ;if(document.getElementById("kit_bienvenida").checked === true){kit_bienvenida = "S";}else{kit_bienvenida = "N";}

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

function star_function(val){
    console.log(val);
    if(val < 1.5){
        return '<span class="fa fa-star checked"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';
    }
    if(val > 1.5 && val < 2.5){
        return '<span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';
    }
    if(val > 2.5 && val < 3.5){
        return '<span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';
    }
    if(val > 3.5 && val < 4.5){
        return '<span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star"></span>';
    }
    if(val > 4.5){
        return '<span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star"></span>';
    }
}
