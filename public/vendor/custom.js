var token = $('meta[name="csrf-token"]').attr('content');
$(document).on('keypress', '#search_cliente', function(){
    var url = window.location.origin+"/clientes/busqueda";
    var key = $("#search_cliente").val();
    $.ajax({
        type: "GET",
        data: { 'keywords': key,'_token': token },
        url: url,
        success: function(data) {
            $(".tbodyModal").html('');
            for (let index = 0; index < data["users"].length; index++) {
                let orders_count = '';
                if(parseInt(data["users"][index]["orders_count"]) > 0){
                    orders_count = '<a href="'+window.location.origin+'/ordenes?usuario='+data["users"][index]["id"]+'">'+data["users"][index]["orders_count"];+'</a>'
                }else{
                    orders_count = data["users"][index]["orders_count"];
                }
                $(".tbodyModal").append(' <tr><td>'+(index+1)+'</td><td>'+data['users'][index]["name"]+' '+data['users'][index]["lastName"]+'</td><td>'+data["users"][index]["email"]+'</td><td>'+data["users"][index]["phone"]+'</td><td>'+orders_count+'</td><td>'+data["users"][index]["created_at"]+'</td></tr>');
            }
        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
});
$(document).on('keypress','#search_tecnico',function(){
    var url = window.location.origin+"/clientes/busqueda_tecnico";
    var key = $("#search_tecnico").val();
    $.ajax({
        type: "GET",
        data: { 'keywords': key,'_token': token },
        url: url,
        success: function(data) {
            $(".tbodyModal").html('');
            for (let index = 0; index < data["users"].length; index++) {
                let state;
                if(data["users"][index]["state"] === 1){
                    state = '<span class="badge badge-success">Validado</span>';
                }else{
                    state = '<span class="badge badge-danger" onclick="aproveFixerMan('+data['users'][index]["id"]+',\''+data['users'][index]["name"]+'\')">Pendiente</span>';
                }
                options = '<div class="table-data-feature"><a class="item" href="'+window.location.origin+'/tecnicos/detalle/'+data['users'][index]["id"]+'"><i data-toggle="tooltip" data-placement="top" title="user" class="zmdi zmdi-eye"></i></a><button class="item" data-toggle="modal" data-target="#mediumImage" id="fixermanModalImage" data-id="'+data['users'][index]["avatar"]+'" data-user="'+data['users'][index]["id"]+'"><i data-toggle="tooltip" data-placement="top" title="user" class="zmdi zmdi-image"></i></button></div>';
                $(".tbodyModal").append(' <tr><td>'+(index+1)+'</td><td>'+data['users'][index]["name"]+' '+data['users'][index]["lastName"]+'</td><td>'+data["users"][index]["email"]+'</td><td>'+data["users"][index]["phone"]+'</td><td>'+moment(data["users"][index]["created_at"]).format("DD/MM/YYYY")+'</td><td>'+state+'</td><td>'+options+'</td></tr>');
            }
        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
});
$(document).ready(function(){
    $(".eliminar").click(function(e) {
        let id = $( this ).attr( "data-id" );
        Swal.fire({
            title: "??Est??s seguro?",
            text: "No podr??s revertirlo!",
            icon: "warning",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: "S??, ??Borrar!",
            cancelButtonText:"Cancelar",
        }).then(function(result) {
            if (result.value) {
                $("#form-eliminar"+id).submit();
            }
        });
    });
});
$(document).ready(function(){
    $(".editar").click(function(e) {
        let id = $( this ).attr( "data-id" );
        Swal.fire({
            title: "??Est??s seguro?",
            text: "No podr??s revertirlo!",
            icon: "success",
            type: 'success',
            showCancelButton: true,
            confirmButtonText: "S??, ??Marcar como resuelto!",
            cancelButtonText:"Cancelar",
        }).then(function(result) {
            if (result.value) {
                $("#form-editar"+id).submit();
            }
        });
    });
});
