
$(document).ready(function () {
  listaEnvioAvaliado();
  listaAvaliador();
    //validarCPF("login_email");
    //validarCPF("processo_email");
});
// preenche os selects com os avaliados de um avaliador 
function listaEnvioAvaliado(){
         
    
         $.ajax({
            url:"src/php/selectsform.php",
            type:"POST",
            data: {
                'acao':'preencheEnvio'
            },
            dataType: "json",

            success: function(nomes){

                var options = '<option value="'+ nomes[0].email+'">' +nomes[0].nome + '</option>';
                for(var i =1; i<nomes.length;i++){   
                    options += '<option value="'+ nomes[i].email+'">' +nomes[i].nome + '</option>';
                 }    
                
                    $("#envio-avaliado").html(options).show(); //preenche  os avaliados no formuçário de envio         
                     $("#form-avaliado").html(options).show(); //preenche  o avaliados no formuçário de avaliação
               
                   
          }
        });
}

/*function listaAvaliado(){
         
        $.ajax({
            url:"src/php/selectsform.php",
            type:"POST",
            data: {
                'acao':'preencheEnvio'
            },
            dataType: "json",

            success: function(nomes){
                

                var options = '<option value=""></option>';
                for(var i =0; i<nomes.length;i++){
                  
                    options += '<option value="'+nomes[i].email+'">' +nomes[i].nome + '</option>';
                 }    
                $("#form-avaliado").html(options).show();
                

            }
        });
}*/

function listaAvaliador(){
         
        $.ajax({
            url:"src/php/selectsform.php",
            type:"POST",
            data: {
                'acao':'preencheAvaliador'
            },
            dataType: "json",

            success: function(response){ 
           
                var options= '<option value="'+response[0].email+'">' +response[0].nome + '</option>';   
                 $("#form-avaliador").html(options).show();     

                         
                          
            }

        });
}

function listaRespondidos(){
         
        $.ajax({
            url:"src/php/selectsform.php",
            type:"POST",
            data: {
                'acao':'preencheRelatorio'
            },
            dataType: "json",

            success: function(response){ 
            
            
           
                var options = '<option value="'+ response[0].email+'">' +response[0].nome + '</option>';
                for(var i =1; i<response.length;i++){   
                    options += '<option value="'+ response[i].email+'">' +response[i].nome + '</option>';
                 }    
                
                   $("#relatorio-ind").html(options).show();          
                    
                         
                        
            }

        });
}


