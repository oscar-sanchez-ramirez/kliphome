<!DOCTYPE html>
<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <title>Pago en línea KlipHome</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="{{url('')}}/vendor/bootstrap/js/bootstrap.js"></script>
        <link href="{{url('')}}/vendor/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
    </head>
    <style>
        #card-form{
            max-width:500px;
            margin:auto;
        }
        #success_view{
            display: none;
        }
        #success_view img{
            margin-top: 10%;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }.center{
            text-align: center;
        }
    </style>
    <body>
        <div id="form_view">
            <form id="card-form">
                <div class="card">
                    <div class="card-header">
                        <div class="row display-tr">
                            <h3>Nuevo pago de ${{ $monto }} </h3>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>
                                    Nombre en Tarjeta
                                </label>
                                <input value="" data-conekta="card[name]" class="form-control" name="name" id="name"  type="text" >
                            </div>
                            <div class="col-md-6">
                                    <label>
                                        Número de Tarjeta
                                    </label>
                                    <input value="" name="card" id="card" data-conekta="card[number]" class="form-control"   type="text" maxlength="16" >
                            </div>
                        </div>

                        <div class="row">
                                <div class="col-md-6">
                                    <label>
                                        CVC
                                    </label>
                                    <input value="" data-conekta="card[cvc]" class="form-control"  type="text" maxlength="4" >
                                </div>
                                <div class="col-md-6">
                                        <label>
                                            Fecha de expiración (MM/AA)
                                        </label>
                                        <div>
                                            <input style="width:50px; display:inline-block" value="" data-conekta="card[exp_month]" class="form-control"  type="text" maxlength="2" >
                                            <input style="width:50px; display:inline-block" value="" data-conekta="card[exp_year]" class="form-control"  type="text" maxlength="2" >

                                        </div>
                                </div>
                        </div>
                        <br>
                        {{-- <label><input type="checkbox" id="guardar_tarjeta" value="false"> Guardar Tarjeta</label><br> --}}
                        <br>
                        <div class="row">
                                <div class="col-md-12" style="text-align:center;">
                                   <button class="btn btn-success btn-lg" id="btnSubmit">
                                       <i class="fa fa-check-square"></i> Pagar
                                   </button>
                                </div>

                        </div>

                    </div>
                </div>
            </form>
        </div>
        <div id="success_view">
            <img src="{{ url('') }}/images/tick.png" height="128px" width="128px" alt="">
            <h3 class="center">Tu pago se realizó</h3>
            <h5>Cierra este modulo para poder validar tu orden</h5>
        </div>
    <script>
        Conekta.setPublicKey("key_bMzSndbgbJXebqbJW9vrrRA");
        var conektaSuccessResponseHandler= function(token){
            jsSave(token.id);
        };

        var conektaErrorResponseHandler =function(response){
            $("#btnSubmit").attr("disabled", false);
            var $form=$("#card-form");
            alert(response.message_to_purchaser);
        }

        $(document).ready(function(){
            $("#card-form").submit(function(e){
                e.preventDefault();
                var $form=$("#card-form");
                $("#btnSubmit").attr("disabled", true);
                Conekta.Token.create($form,conektaSuccessResponseHandler,conektaErrorResponseHandler);
            })

        })

        function jsSave(token_id){

            let params=$("#card-form").serialize();
            let guardar_tarjeta = $('#guardar_tarjeta').is(":checked");
            var url = "{{ url('') }}/api/conekta_nuevo_pago";
            $.ajax({
                type: "POST",
                url: url,
                data: { 'token': token_id,'user_id':{{ $user_id }},'type':"{{ $type }}",'monto':{{ $monto }},'guardar_tarjeta':guardar_tarjeta },
                success: function(data) {
                    if(data.success){
                        jsClean();
                        $("#success_view").show();
                        $("#form_view").hide();
                    }else{
                        alert("Error al realizar pago, Porfavor intente de nuevo");
                        $("#btnSubmit").attr("disabled", false);
                    }
                },
                error: function(data) {
                    $("#btnSubmit").attr("disabled", false);
                    alert("Error al realizar pago, Porfavor intente de nuevo");
                }
            });
        }

        function jsClean(){
            $(".form-control").prop("value","");
        }
    </script>

    </body>
</html>
