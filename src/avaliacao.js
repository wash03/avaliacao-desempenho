//submit dos dados do form para o BD
$("#btn-avaliacao").click(function (element){
    var tipo=$("#form-tipo").val();
    var avaliado=$("#form-avaliado").val();
    var avaliador=$("#form-avaliador").val();
    var data=$("#data").val();
    var tamanho=0;
    
    
    if(!data){
        exibirModal("ALERTA","É necessário informar a data!");
    }
     var display = {
        avaliadorRespostas: (($(".avaliado-control :input").prop("disabled"))?0:1),
        avaliadorRespostas: (($(".avaliador-control :input").prop("disabled"))?0:1),
        consensoRespostas: (($(".consenso-control :input").prop("disabled"))?0:1),
    };

    if($('#lideranca').is(':visible')){
         tamanho=14;
         
    }
    else{

         tamanho =10;
    }
    if(tipo==2){
        
         if($(".avaliador-control :checked").length==tamanho){            
            salvarDados(".avaliador-control",tamanho);
            fechaForm();
        }else{
            exibirModal("ALERTA","Algumas questões não estão respondidas!");
        }

    }
    else if (tipo==3 ){
        if($(".avaliado-control :checked").length==tamanho){
            
            salvarDados(".avaliado-control",tamanho);
            fechaForm();
        }else{
            exibirModal("ALERTA","Algumas questões não estão respondidas!");
        }
    }
    
    else if(tipo==1 ){
        if($(".consenso-control :checked").length==tamanho){          
           salvarDados(".consenso-control",tamanho);           
            geraMedia(".consenso-control",tamanho,1);
            setTimeout(function(){ window.print(); }, 4000);
        }else{
            exibirModal("ALERTA","Algumas questões não estão respondidas!");
        }

    }
    else{
        exibirModal("ALERTA","Erro ao enviar formulário");
    }
   return false;
   
});
