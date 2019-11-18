var category_title;
$(document).on('click', '#SubcategoryModal', function(){
    //Getting id and title of current category
    var category_id = $(this).attr('data-id');
    category_title = $(this).attr('data-title');
    //Display title into modal header
    $('#mediumModalLabel').html('Subcategorías de '+category_title);
    //Setting category_id into input hidden
    $("#categoryIdModal").val(category_id);
    //Listing all sub categories into modal
    listSubCategories(category_id);
});

function saveSubCategory(){
    //Getting the new title and category_id for new record
    var subcategory_title = document.getElementById('titleModal').value;
    var category_id = document.getElementById('categoryIdModal').value;
    //Importing the token and declaring the URL
    var token = $('meta[name="csrf-token"]').attr('content');
    var url = window.location.origin+"/sub-categorias/nuevo";
    $.ajax({
        type: "POST",
        url: url,
        data: { 'subcategory_title': subcategory_title, 'category_id':category_id,'_token': token },
        success: function(data) {
            //Listing all sub categories into modal when record has been saved
            listSubCategories(category_id);
        },
        error: function(data) {
            alert("Error al guardar registro, Porfavor intente de nuevo");
        }
    });
}
$('#newSubCategory').on('click',function(){
    //Display new row into modal for fill new record
    $('.tbodyModal').append(' <tr><td>'+category_title+'</td><td><input class="form-control" type="text" name="titleModal" id="titleModal"></td><td><button type="button" class="btn btn-success btn-sm" id="SaveElementModalSubCategory" onclick="saveSubCategory()">Save</button></td></tr>');
});
function listSubCategories(category_id){
    //Listing subcategories by category_id
    var url = window.location.origin+"/getSubcategory/"+category_id;
    $.ajax({
        type: "GET",
        url: url,
        success: function(data) {
            if (data != "") {
                $(".tbodyModal").html(data);
            } else {

                $(".tbodyModal").html('<p>No tiene Subcategorías</p>');
            }

        },
        error: function(data) {
            console.log("Error al obtener en busqueda");
        }
    });
}