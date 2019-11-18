var service_title;
$(document).on('click', '#SubServiceModal', function(){
    //Getting id and title of current service
    var service_id = $(this).attr('data-id');
    service_title = $(this).attr('data-title');
    //Display title into modal header
    $('#mediumModalLabel').html('Sub-Servicios de '+service_title);
    //Setting service_id into input hidden
    $("#serviceIdModal").val(service_id);
    //Listing all sub services into modal
    listSubServices(service_id);
});

function saveSubService(){
    //Getting the new title and service_id for new record
    var subservice_title = document.getElementById('titleModal').value;
    var service_id = document.getElementById('serviceIdModal').value;
    //Importing the token and declaring the URL
    var token = $('meta[name="csrf-token"]').attr('content');
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