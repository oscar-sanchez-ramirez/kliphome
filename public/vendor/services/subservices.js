var service_title;
var service_id;
//Importing the token
var token = $('meta[name="csrf-token"]').attr('content');

$(document).on('click', '#SubServiceModal', function(){
    //Getting id and title of current service
    service_id = $(this).attr('data-id');
    service_title = $(this).attr('data-title');
    //Display title into modal header
    $('#mediumModalLabel').html('Sub-Servicios de '+service_title);
    //Setting service_id into input hidden
    $("#serviceIdModal").val(service_id);
    //Listing all sub services into modal
    listSubServices(service_id);
});
$("#filterCategories").on('change',function(){
    var category = document.getElementById('filterCategories').value;
    listServices(category);
    // console.log(category);
});

function saveSubService(){
    //Getting the new title and service_id for new record
    var subservice_title = document.getElementById('titleModal').value;
    var service_id = document.getElementById('serviceIdModal').value;
    //Generating the url
    var url = window.location.origin+"/sub-servicios/nuevo";
    $.ajax({
        type: "POST",
        url: url,
        data: { 'subservice_title': subservice_title, 'service_id':service_id,'_token': token },
        success: function(data) {
            //Listing all sub services into modal when record has been saved
            listSubServices(service_id);
        },
        error: function(data) {
            alert("Error al guardar registro, Porfavor intente de nuevo");
        }
    });
}
$('#newSubService').on('click',function(){
    //Display new row into modal for fill new record
    $('.tbodyModal').append(' <tr><td>'+service_title+'</td><td><input class="form-control" type="text" name="titleModal" id="titleModal"></td><td><button type="button" class="btn btn-success btn-sm" id="SaveElementModalSubService" onclick="saveSubService()">Save</button></td></tr>');
});

function setUpdateField(subservice){
    //Showing the current subservice to update
    $(".td_"+subservice["id"]).html('<input type="text" class="form-control" id="subservicename_update" name="subservicename_update" value="'+subservice["title"]+'">');
    $(".td_"+subservice["id"]).closest('td').next().html('<td> <div class="table-data-feature"><button class="item" data-toggle="tooltip" data-placement="top" title="Close" onclick="listSubServices('+subservice["id"]+')"><i class="zmdi zmdi-close"></i></button><button class="item" data-toggle="tooltip" data-placement="top" title="Save" onclick="updateSubService('+subservice["id"]+')"><i class="zmdi zmdi-check"></i></button></div></td>');
}

function updateSubService(subservice_id){
    var url = window.location.origin+"/sub-servicios/actualizar";
    var subservice_title = $('#subservicename_update').val();
    $.ajax({
        type: "POST",
        url: url,
        data: { 'subservice_id': subservice_id,'_token': token, 'subservice_title':subservice_title },
        success: function(data) {
            //Listing all sub categories into modal when record has been saved
            listSubServices(service_id);
        },
        error: function(data) {
            alert("Error al guardar registro, Porfavor intente de nuevo");
        }
    });
}

function deleteSubService(subservice_id){
    var url = window.location.origin+"/sub-servicios/eliminar";
    $.ajax({
        type: "POST",
        url: url,
        data: { 'subservice_id': subservice_id,'_token': token },
        success: function(data) {
            //Listing all sub services into modal when record has been saved
            listSubServices(service_id);
        },
        error: function(data) {
            alert("Error al eliminar registro, Porfavor intente de nuevo");
        }
    });
}
function listSubServices(service_id){
    //Listing sub services by service_id
    var url = window.location.origin+"/getSubservice/"+service_id;
    $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            if (data != "") {
                $(".tbodyModal").html(data);
            } else {

                $(".tbodyModal").html('<p>No tiene Sub Servicios</p>');
            }

        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
}

function listServices(category_id){
    //Listing sub services by service_id
    var url = window.location.origin+"/getServices/"+category_id;
    $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            console.log(data);
            if (data != "") {
                $("#bodyServices").html(data);
            } else {

                $("#bodyServices").html('<p>No tiene Servicios</p>');
            }

        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
}