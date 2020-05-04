<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Detalle de Técnico</h5>
                <input type="hidden" id="categoryIdModal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <h4>Ficha :</h4>
                    <div id="ficha">
                        <div class="form-check"><input type="checkbox" class="form-check-input" id="prueba_psicologica"><label class="form-check-label" for="exampleCheck1">Prueba Psicologica</label></div>
                        <div class="form-check"><input type="checkbox" class="form-check-input" id="acuerdo_laboral"><label class="form-check-label" for="exampleCheck1">Acuerdo Laboral</label></div>
                        <div class="form-check"><input type="checkbox" class="form-check-input" id="comprobante_domicilio"><label class="form-check-label" for="exampleCheck1">Comprobante de domicilio</label></div>
                        <div class="form-check"><input type="checkbox" class="form-check-input" id="asistencia_entrevista"><label class="form-check-label" for="exampleCheck1">Asistencia a entrevista</label></div>
                        <div class="form-check"><input type="checkbox" class="form-check-input" id="copia_dni"><label class="form-check-label" for="exampleCheck1">Copia de identificación oficial</label></div>
                        <div class="form-check"><input type="checkbox" class="form-check-input" id="foto"><label class="form-check-label" for="exampleCheck1">Foto</label></div>
                        <div class="form-check"><input type="checkbox" class="form-check-input" id="kit_bienvenida"><label class="form-check-label" for="exampleCheck1">Kit de bienvenida</label></div>
                    </div>
                </form>
                <h4>Delegación seleccionada :</h4>
                <p id="fixerManDelegation"></p>
                <h4>Categorías seleccionadas :</h4>
                <p id="fixerManCategories"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumImage" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Imagen </h5>
                <input type="hidden" id="categoryIdModal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('') }}/tecnicos/updateFixermanImage" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <img id="imageFixerman" src=""  style="border-radius:100%">
                            <input type="hidden" id="idFixerman" name="idFixerman" value="">
                            <input type="hidden" name="url" value="{{url('')}}">
                        </div>
                        <div class="col-md-8" style="text-align:center">
                            <input type="file" name="imagen" required>
                            <br><br><br>
                            <button type="submit" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                </i>Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="fichatecnica" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Ficha Técnica</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <h4>Ficha :</h4>
                    <div id="ficha"></div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
