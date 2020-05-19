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
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Ficha</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Menu 2</a>
                    </li>
                </ul>
                <div class="tab-content pl-3 p-1" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <input type="hidden" id="idFixerman" name="idFixerman" value="">
                        <div id="ficha">
                            <div class="form-check"><input type="checkbox" class="form-check-input" name="prueba_psicologica" id="prueba_psicologica"><label class="form-check-label" for="exampleCheck1">Prueba Psicologica</label></div>
                            <div class="form-check"><input type="checkbox" class="form-check-input" name="acuerdo_laboral" id="acuerdo_laboral"><label class="form-check-label" for="exampleCheck1">Acuerdo Laboral</label></div>
                            <div class="form-check"><input type="checkbox" class="form-check-input" name="comprobante_domicilio" id="comprobante_domicilio"><label class="form-check-label" for="exampleCheck1">Comprobante de domicilio</label></div>
                            <div class="form-check"><input type="checkbox" class="form-check-input" name="asistencia_entrevista" id="asistencia_entrevista"><label class="form-check-label" for="exampleCheck1">Asistencia a entrevista</label></div>
                            <div class="form-check"><input type="checkbox" class="form-check-input" name="copia_dni" id="copia_dni"><label class="form-check-label" for="exampleCheck1">Copia de identificación oficial</label></div>
                            <div class="form-check"><input type="checkbox" class="form-check-input" name="foto" id="foto"><label class="form-check-label" for="exampleCheck1">Foto</label></div>
                            <div class="form-check"><input type="checkbox" class="form-check-input" name="kit_bienvenida" id="kit_bienvenida"><label class="form-check-label" for="exampleCheck1">Kit de bienvenida</label></div>
                        </div>
                        <button class="btn btn-primary" onclick="guardar_ficha()">Guardar</button>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h4>Delegación seleccionada :</h4>
                        <p id="fixerManDelegation"></p>
                        <h4>Categorías seleccionadas :</h4>
                        <p id="fixerManCategories"></p>
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <h3>Calificaciones</h3>
                        <div id="fixerManReviews"></div>
                    </div>
                </div>

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
