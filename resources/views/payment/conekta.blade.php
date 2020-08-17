<!DOCTYPE html>
<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <title>Pago en línea</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="{{url('')}}/vendor/bootstrap/js/bootstrap.js"></script>
        <link href="{{url('')}}/vendor/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <style>
        #card-form{
            max-width:500px;
            margin:auto;
        }
    </style>
    <body>
        <form id="card-form">

            <input type="hidden" name="conektaTokenId" id="conektaTokenId" value="">

            <div class="card">
                <div class="card-header">
                    <div class="row display-tr">
                        <h3>Pago de </h3>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>
                                Nombre en Tarjeta
                            </label>
                            <input value="e.g. John Doe" data-conekta="card[name]" class="form-control" name="name" id="name"  type="text" >
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
                    {{-- <div class="row">
                        <div class="col-md-4">
                            <label><span>Email</span></label>
                            <input class="form-control" type="text" name="email" id="email" maxlength="200" value="pepepecaspicapapasconunpico666@gmail.com">
                        </div>
                        <div class="col-md-4">
                            <label>Concepto</label>
                            <input class="form-control" type="text" name="description" id="description" maxlength="100" value="papitas">
                        </div>
                        <div class="col-md-4">
                            <label>Monto</label>
                            <input class="form-control" type="number" name="total" id="total" value="30">
                        </div>

                    </div> --}}
                    <br>
                    <div class="row">
                            <div class="col-md-12" style="text-align:center;">
                               <button class="btn btn-success btn-lg">
                                   <i class="fa fa-check-square"></i> PAGAR
                               </button>
                            </div>

                    </div>

                </div>
            </div>


        </form>
    <script>
        Conekta.setPublicKey("key_bMzSndbgbJXebqbJW9vrrRA");

        var conektaSuccessResponseHandler= function(token){
            console.log(token);
            $("#conektaTokenId").val(token.id);
            //jsPay(token.id);
        };

        var conektaErrorResponseHandler =function(response){
            var $form=$("#card-form");
            console.log(response);
            alert(response.message_to_purchaser);
        }

        $(document).ready(function(){

            $("#card-form").submit(function(e){
                e.preventDefault();
                var $form=$("#card-form");
                Conekta.Token.create($form,conektaSuccessResponseHandler,conektaErrorResponseHandler);
            })

        })

        function jsPay(token_id){
            let params=$("#card-form").serialize();
            console.log(params);
            var token = $('meta[name="csrf-token"]').attr('content');
            var url = "";
            // $.ajax({
            //     type: "POST",
            //     url: url,
            //     data: { 'token_id': token_id,'_token': token, 'subcategory_title':subcategory_title },
            //     success: function(data) {
            //         //Listing all sub categories into modal when record has been saved
            //         listSubCategories(category_id);
            //     },
            //     error: function(data) {
            //         alert("Error al guardar registro, Porfavor intente de nuevo");
            //     }
            // });
            // $.post(url,params,function(data){
            //     if(data=="1"){
            //         alert("Se realizo el pago :D");
            //         jsClean();
            //     }else{
            //         alert(data)
            //     }

            // });

        }

        function jsClean(){
            $(".form-control").prop("value","");
            $("#conektaTokenId").prop("value","");
        }
    </script>

    </body>
</html>
