$("button").click(function(){
    listaAvaliado();
    
});

function listaAvaliado(){
 
        $.ajax({
            url:"teste2.php",
            type:"POST",
            data: {
                acao:'preenche'
            },
            dataType: "json",

            success: function(nomes){
                
                console.log(nomes);

            }
        });
}

$(document).ready(function() {
    listaAvaliado();
});

function listaAvaliado(){
 
        $.ajax({
            url:"teste2.php",
            type:"POST",
            data: {
                acao:'preenche'
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
}


//submit dos dados do form para o BD
$("#form-avaliacao").submit(function (element){
    alert("enviou");
    var display = {
        avaliado: (($(".avaliado-control :input").prop("disabled"))?0:1),
        avaliador: (($(".avaliador-control :input").prop("disabled"))?0:1),
        consenso: (($(".consenso-control :input").prop("disabled"))?0:1),
    };
    alert("Salve suas respostas!");
    window.print();
    if(display.avaliado){
        if($(".avaliado-control :checked").length==56/4){
            salvarDados(".avaliado-control");
        }else{
            exibirModal("ALERTA","Algumas questões não estão respondidas!");
        }
    }else if(display.avaliador){
        if($(".avaliador-control :checked").length==56/4){
            salvarDados(".avaliador-control");
        }else{
            exibirModal("ALERTA","Algumas questões não estão respondidas!");
        }
    }else if(display.consenso){
    
        if($(".consenso-control :checked").length==56/4){
            salvarDados(".consenso-control");
        }else{
            exibirModal("ALERTA","Algumas questões não estão respondidas!");
        }
    }
    return false;
});


 

