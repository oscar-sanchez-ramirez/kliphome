<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Medium Modal</h5>
                <input type="hidden" id="serviceIdModal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Sub-Servicio</th>
                            <th><button class="au-btn au-btn-icon au-btn--green au-btn--small" id="newSubService">Nuevo</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="tbodyModal">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- modal static -->
<div class="modal fade" id="staticModal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true"
data-backdrop="static">
   <div class="modal-dialog modal-sm" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="staticModalLabel">Info</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body">
               <p>
                   En esta vista podrás administrar los servicios. Dichos elementos se listarán en la aplicación de clientes.
               </p>
               <p>Además podrás ver en el listado a que Sub-Categoría y Categoría pertecen. Finalmente podrás adicionar SubServicios si fuera necesario</p>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
           </div>
       </div>
   </div>
</div>
<!-- end modal static -->
