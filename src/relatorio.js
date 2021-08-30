
//acessa relatorios
   $("#btnacessa").click(function(){
    $(".row-relat").css('display','none');
         $.post('src/php/relatorio.handler.php', {
           'acessoRelat':1
       }, function(response){
        if(response==2  ){
           $(".gera-relatorio").css("display","block");
           $("#acesso-geral").css("display","block");
            $("#acesso-btn").css("display","none");
            listaTodosConsesnsos();
             listaSetor(2);
           
        }
        else if( response==3){
           $(".gera-relatorio").css("display","block");
             $("#acesso-btn").css("display","none");
             listaSetor(3);

        }
        else if(response==4){ 
            exibirModal('INFORMATIVO','Você não possui acesso aos relatórios');
     
        }
         else if(response==1){
            exibirModal('INFORMATIVO','Você não está logado');
     
        }
        
      else{
        exibirModal('INFORMATIVO','Algo deu errado');
      }

       });
    
});


function listaSetor(tipo){
    

         $.ajax({
            url:"src/php/selectsform.php",
            type:"POST",
            data: {'acao':'preencheSetor','tipo':tipo},
            
            success: function(response){        
            
            var b  = JSON.parse(response);
           
                var options = '<option value="'+ b[0].responsavel+'">' +b[0].nome + '</option>';
                for(var i =1; i< b.length; i++){   
                    options += '<option value="'+ b[i].responsavel+'">' +b[i].nome + '</option>';
                 }    
                
                   $("#relatorio-set").html(options).show();   
                    $("#func-set").html(options).show();                   
                        
            }
            ,
           error: function( error ){

           // alert(JSON.stringify(error));

           }

        });
}
function listaTodosConsesnsos(){
    

         $.ajax({
            url:"src/php/selectsform.php",
            type:"POST",
            data: {'acao':'listaTodosConsesnsos'},
            
            success: function(response){        
            
            var b  = JSON.parse(response);
           
                var options = '<option value="'+ b[0].email+'">' +b[0].nome + '</option>';
                for(var i =1; i< b.length; i++){   
                    options += '<option value="'+ b[i].email+'">' +b[i].nome + '</option>';
                 }    
                
                   $("#funcionario-relat").html(options).show();   
                                  
                        
            }
            ,
           error: function( error ){

           // alert(JSON.stringify(error));

           }

        });
}


//acessa relatorios
   $("#btnrelat-set").click(function(){
      var setor =$("#relatorio-set option:selected").val();
      if(!setor){
        return false;
      }
     criaRelatorio(setor);
      $(".geral").css('display','none');
      $(".row-relat").css("display","block");
      $("#individual-geral").css("display","none");
      $("#individual-geral").css("display","none");
     
        
 
});
  
 
  function relatorioGeral(){
     $("#relatorio-set ").val(" ");
    $(".geral").css('display','block');
     $(".row-relat").css("display","block");
      $("#individual-geral").css("display","none");
    criaRelatorio(0);
  }



    
  function criaRelatorio(setor){
    relatorioTopico(setor); // conceito de cada topico do setor
    mediaTotalSetor(setor); // media do setor
    conceitoFuncionario(setor);
    titulo();
    if(setor==0){
       porcentagemconceito();
       porcetnagemConceitoGerente();  
       conceitoSetores();
       topicoNãoGerente();
       topicoGerente();
       mediaTotalNaoGerente();
       mediaTotalGerente();
       formulariosRespondidos();

    }
   
  }

function formulariosRespondidos(){

    $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'FormsResp':1},
            
            success: function(response){             
             $("#porc-ng").html(response.porcentagem +'%');
             $("#porc-ng").css("width", response.porcentagem+'%');
              
            }
            ,
           error: function(error){
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');
               

            //alert(JSON.stringify(error));

           }

        });
    $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'FormsRespGerente':1},
            
            success: function(response){       
              var porc=   response.porcentagem +'%';
              $("#porc-gerente").html(porc);
              $("#porc-gerente").css("width", porc);
               
            }
            ,
           error: function(error){
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');
               

            //alert(JSON.stringify(error));

           }

        });

}

function titulo(){
  var setor =  $("#relatorio-set option:selected").text();
  if(!setor){
    $("#titulo-relatorio").text("GERAL"); 
  }
  else{
   $("#titulo-relatorio").text(setor); 
  }
}
 function mediaTotalSetor(setor){
     $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'MedSetor':1,'setor':setor},
            
            success: function(response){  
              

             if(response.letra=='A'){
                $("#mediaA-relat").fadeIn();
                $("#mediaB-relat").css('display','none');
                $("#mediaC-relat").css('display','none');
                $("#mediaD-relat").css('display','none');

            }
            else if(response.letra=='B'){
                $("#mediaB-relat").fadeIn();
                $("#mediaA-relat").css('display','none');
                $("#mediaC-relat").css('display','none');
                $("#mediaD-relat").css('display','none');
                

            }
            else if(response.letra=='C'){
                $("#mediaC-relat").fadeIn();
                $("#mediaA-relat").css('display','none');
                $("#mediaB-relat").css('display','none');
                $("#mediaD-relat").css('display','none');
               

            }
            else if(response.letra=='D'){
                $("#mediaD-relat").fadeIn(); 
                $("#mediaA-relat").css('display','none'); 
                $("#mediaB-relat").css('display','none');  
                $("#mediaC-relat").css('display','none');         

            }   
            else if(response=='sem'){

                exibirModal("INFORMATIVO","Sem relatório disponível");
                 $(".row-relat").css('display','none');

                
                
                
            }
            else{
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');
              //alert(JSON.stringify(response));
            }
            
                             
                        
            }
            ,
           error: function( error ){
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');
               

            //alert(JSON.stringify(error));

           }

        });

 }



 function mediaTotalNaoGerente(){
     $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'MedNaoGerente':1},
            
            success: function(response){  
              

             if(response.letra=='A'){
                $("#mediaA-ng").fadeIn();
                $("#mediaB-ng").css('display','none');
                $("#mediaC-ng").css('display','none');
                $("#mediaD-ng").css('display','none');

            }
            else if(response.letra=='B'){
                $("#mediaB-ng").fadeIn();
                $("#mediaA-ng").css('display','none');
                $("#mediaC-ng").css('display','none');
                $("#mediaD-ng").css('display','none');
                

            }
            else if(response.letra=='C'){
                $("#mediaC-ng").fadeIn();
                $("#mediaA-ng").css('display','none');
                $("#mediaB-ng").css('display','none');
                $("#mediaD-ng").css('display','none');
               

            }
            else if(response.letra=='D'){
                $("#mediaD-ng").fadeIn(); 
                $("#mediaA-ng").css('display','none'); 
                $("#mediaB-ng").css('display','none');  
                $("#mediaC-ng").css('display','none');         

            }   
            else if(response=='sem'){

                exibirModal("INFORMATIVO","Sem relatório disponível");
                 $(".row-relat").css('display','none');

                
                
                
            }
            else{
              exibirModal("INFORMATIVO","Algo deu errado");
              //alert(JSON.stringify(response));
            }
            
                             
                        
            }
            ,
           error: function( error ){
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');            

            //alert(JSON.stringify(error));

           }

        });

 }


 function mediaTotalGerente(){
     $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'MedGerente':1},
            
            success: function(response){  
              

             if(response.letra=='A'){
                $("#mediaA-gerente").fadeIn();
                $("#mediaB-gerente").css('display','none');
                $("#mediaC-gerente").css('display','none');
                $("#mediaD-gerente").css('display','none');

            }
            else if(response.letra=='B'){
                $("#mediaB-gerente").fadeIn();
                $("#mediaA-gerente").css('display','none');
                $("#mediaC-gerente").css('display','none');
                $("#mediaD-gerente").css('display','none');
                

            }
            else if(response.letra=='C'){
                $("#mediaC-gerente").fadeIn();
                $("#mediaA-gerente").css('display','none');
                $("#mediaB-gerente").css('display','none');
                $("#mediaD-gerente").css('display','none');
               

            }
            else if(response.letra=='D'){
                $("#mediaD-gerente").fadeIn(); 
                $("#mediaA-gerente").css('display','none'); 
                $("#mediaB-gerente").css('display','none');  
                $("#mediaC-gerente").css('display','none');         

            }   
            else if(response=='sem'){

                exibirModal("INFORMATIVO","Sem relatório disponível");
                 $(".row-relat").css('display','none');

                
                
                
            }
            else{
              exibirModal("INFORMATIVO","Algo deu errado");
              //alert(JSON.stringify(response));
            }
            
                             
                        
            }
            ,
           error: function( error ){
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');            

            //alert(JSON.stringify(error));

           }

        });

 }



function funcionarioSetor(){
  var setor =$("#func-set option:selected").val();
  conceitoFuncionario(setor);

}


function consensosPendentes(){

  $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'ConPen':1},
            
            success: function(response){  
             //const dados = JSON.parse(response);
             var nomes= response[0].nome +'<br>';
         for(let index = 1; index < response.length; index++) {
            nomes= nomes + response[index].nome +  '<br>';                                  
         }

         exibirModal("Consensos pendentes: "+response.length,nomes);     
                                                                              
          }
            ,
           error: function( error ){
              exibirModal("INFORMATIVO","Algo deu errado");
                   
              //alert(error);
           }

  });

}
function mostraFuncionarios(){
  $(".row-relat").css("display","none");
  $("#individual-geral").css("display","block");
  $("#relatorio-set ").val(" ");

}

function carregaRelatorioIndividual(){ 
    var avaliado=$("#funcionario-relat option:selected").val();
    var nome =$("#funcionario-relat option:selected").text();
      

      $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'avaliado':avaliado},
            
            success: function(response){  
             
              avaliador=response[0].email;             
              $('#form-avaliador').html('<option value='+  response[0].email + '>'+ response[0].nome +'</option>').show();
              $('#form-avaliado').html('<option value='+  avaliado+ '>'+ nome +'</option>').show();
              $("#form-avaliador").val(avaliador);
              $("#form-avaliado").val(avaliado);
              
              retornaConsenso(avaliado,avaliador); 
             
             //const dados = JSON.parse(response);   
                                                                              
          }
            ,
           error: function( error ){
              //exibirModal("INFORMATIVO","Algo deu errado");
                   
              alert(error);
           }

  });
    //

}