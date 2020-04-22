
<!-- modal scroll -->
<div class="modal fade" id="scrollmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scrollmodalLabel">Imagen del servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-heigth:500px;overflow:scroll">
                <div>
                    <img  src="{{ ($orden->service_image) }}" style="transform:rotate(90deg);" alt="Card image cap" >
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal scroll -->
<!-- fixerman modal -->
<div class="modal fade" id="fixermanModal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scrollmodalLabel">Listado de Técnicos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-heigth:500px;overflow:scroll">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Nombres</th>
                            <th>Telefono</th>
                            <th>Servicios</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="tbodyModal">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>
<!-- end fixerman modal -->
<!-- modal quotation -->
<div class="modal fade" id="quotationmodal" tabindex="-1" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scrollmodalLabel">Cotización</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-heigth:500px;overflow:scroll">
                <form class="au-form-icon" action="{{ url('') }}/ordenes/enviarCotizacion/{{ $orden->id }}" method="POST">
                    @csrf

                    <textarea name="solution" class="au-input au-input--full my-editor" cols="10" rows="3" placeholder="Explica la solución al problema"></textarea>
                    <textarea name="materials" class="au-input au-input--full my-editor" cols="10" rows="3" placeholder="Explica los materiales necesarios"></textarea>
                        <br><br>
                    <input class="au-input au-input--full au-input--h65" type="number" name="price" placeholder="Escribe un precio: Ejemplo:300">
                    <button type="submit" class="btn btn-primary">Enviar a {{ $orden->clientName($orden->user_id)["name"] }}</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script src="{{url('')}}/js/tinymce.min.js"></script>
  <script>
    var editor_config = {
    path_absolute : "/",
    selector: "textarea.my-editor",
    plugins: [
      "advlist autolink lists link charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
    relative_urls: false
  };
    tinymce.init(editor_config);
</script>
<!-- end modal quotation -->