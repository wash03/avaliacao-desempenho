$("#login").submit(function (e) {
    let inputs = $("#login :input");
    let valores={
        email: $(inputs[0]).val().toLowerCase().trim(),
        senha: $(inputs[1]).val().trim(),
        login: 1,
    };
    $.ajax({
        type: "post",
        url: "src/php/avaliador.handler.php",
        data: valores,
        success: function (r) {
            $(".inicia-processo").fadeOut();
            $(".alterar-senha").fadeOut();
            $(".relatorio-individual").fadeOut();
            if(r=="erro"){
                exibirModal("Aviso","Crendenciais incorretas!");
            }else if(r=="logado"){
                $(".logado").fadeIn();
                $(".login").fadeOut();
                listaEnvioAvaliado();
                listaAvaliador();

            }else{
            
                limparSelect();
                resetarAvalicao();
                var ar=r.split("/");
                $(".logado").fadeIn();
                $(".login").fadeOut();
                var arLength=ar.length-1;
                var funcionario;
                if(arLength>0){
                    exibirModal("Aviso","Você tem "+arLength+" formulário(s) em aberto");
                }
                for (let index = 1; index < (arLength+1); index++) {
                    funcionario = JSON.parse(ar[index]);
                    addOption("#form-avaliado",funcionario.nome_avaliado,funcionario.email_avaliado,true);
                }
                addOption("#form-avaliador",funcionario.nome_avaliador,funcionario.email_avaliador,true);
                carregarAvaliacao();
            }
        }
    });
    return false;
});

$("#form-processo").submit(function (e) { 
    let inputs = $("#form-processo :input");
    let valores={
        nome:$(inputs[0]).val().toUpperCase().trim(),
        email:$(inputs[1]).val().toLowerCase().trim(),
        empresa:$(inputs[2]).val().toUpperCase().trim(),
        setor:$(inputs[3]).val().toUpperCase().trim(),
        cargo:$(inputs[4]).val().toUpperCase().trim(),
        dataCargo:$(inputs[5]).val(),
        isAvaliador:$(inputs[6]).val(),
        isGestor: $(inputs[7]).val(),
        cadavaliador:$("#cadavaliador").val(),
        processo: 1,
    };
    
    if(dateDiff(valores.dataCargo)<182){
        exibirModal("INFORMATIVO","Não poderá ser avaliado funcionários com menos de 6 meses no cargo!");
        return false;
    }
    $.ajax({
        type: "post",
        url: "src/php/avaliador.handler.php",
        data: valores,
        success: function (r) {
            
            limparSelect();
            resetarAvalicao();
             if(r=="nao logado"){
                exibirModal("INFORMATIVO","Você não está logado!");
            }else if(r=="Erro de conexão"){
                exibirModal("INFORMATIVO","Problema de conexão.Entre em contato com o suporte!");
            }else if(r=="cadastrado"){ 
                exibirModal("INFORMATIVO","Funcionário Cadastrado.");
                listaEnvioAvaliado();
            }else if(r=="atualizado"){ 
               exibirModal("INFORMATIVO","Funcionário Atualizado.");
            }else{        
               
                exibirModal("Aviso","Algo deu errado.");
            } 
        }
    });
    return false;
});

$("#alterar-senha").submit(function (e) { 
    let inputs = $("#alterar-senha :input");
    let valores={
        senha: $(inputs[0]).val().trim(),
        repetirSenha: $(inputs[1]).val().trim(),
        alterarSenha: 1,
    };
    if(valores.senha != valores.repetirSenha){
        exibirModal("Aviso","Senhas não coincidem!");
    }
    else{
          $.ajax({
        type: "post",
        url: "src/php/avaliador.handler.php",
        data: valores,
        success: function (response) {
            if(response=="nao loagado"){
                exibirModal("Aviso","Você não está logado!");
            }else if(response == "ok"){
                exibirModal("Aviso","Senha Alterada com sucesso!");
                $(".alterar-senha").fadeOut();
                $(inputs[0]).val("");
                $(inputs[1]).val("");
            }
        }
    });
    }
  
    return false;
});

$("#btnenvia-avaliado").click(function () { 
    var avaliado =$("#envio-avaliado option:selected").val();
     
    if(!avaliado){
       exibirModal("Aviso","Preencha todos os campos.");
    }
    else{
          $.post('src/php/avaliador.handler.php', {
           'avaliado':avaliado}, function(response){
            if(response=="erro"){
                exibirModal("Aviso","Erro.");
            }else if(response=="jaenviou"){
                exibirModal("Aviso","Já foi enviado anteriormente.");

            }
            else{
               exibirModal("Aviso","Formulário enviado");
            }
            
              
       });

    }
  
    return false;
});

function geraAvaliacao(r){
    listaEnvioAvaliado();
    var options = '<option value="'+r.email+'">' +r.nome + '</option>';
    $("#form-avaliador").html(options).show();   
}

function exibirProcesso() {
    $(".alterar-senha").fadeOut();
    $(".relatorio-individual").fadeOut();
    $(".envia-form").fadeOut();
    $(".inicia-processo").fadeIn();
}

function exibirRelatorioIndividual() {
    $(".inicia-processo").fadeOut();
    $(".alterar-senha").fadeOut();
    $(".envia-form").fadeOut();
    $(".relatorio-individual").fadeIn();
    listaRespondidos();
}

function exibirAlterarSenha() {
    $(".inicia-processo").fadeOut();
    $(".relatorio-individual").fadeOut();
    $(".envia-form").fadeOut();
    $(".alterar-senha").fadeIn();
}

function exibirEnviaForm() {
    $(".inicia-processo").fadeOut();
    $(".relatorio-individual").fadeOut();
    $(".alterar-senha").fadeOut();
    $(".envia-form").fadeIn();
    
}

function autoavaliados() {
      $.post('src/php/avaliador.handler.php', {
           'acao':'exibirAutoavaliados'
       }, function(response){
        const dados = JSON.parse(response);
        var nomes= dados[0].nome +'<br>';
         for(let index = 1; index < dados.length; index++) {
            nomes= nomes + dados[index].nome + '<br>';                                  
         }

         exibirModal("Funcionários que ainda não responderam a autoavaliação",nomes);     
                   
       });
}
function getConsenso() {
    var avaliado =$("#relatorio-ind option:selected").val();
    $("#form-avaliado").val(avaliado);
    var avaliador=$("#form-avaliador option:selected").val();
    retornaConsenso(avaliado,avaliador);
     
}

function retornaConsenso(avaliado,avaliador) {
    
        
        $("#form-tipo").val('1');
        $.post('src/php/respostas.php', {
           'avaliado':avaliado,'avaliador':avaliador,'tipo':1,'acao': 'recuperarForm'
       }, function(response){
            var tam=0;
       
            if(response==1 || response==6){
                if(response==1){
                     carregarAvaliacao(1,0);
                     tam=10;
                }
                else{
                    carregarAvaliacao(1,1);
                    tam=14;
                }
            $.post('src/php/respostas.php', {
           'acao':'preencheRespostaConsenso','avaliado':avaliado, 'avaliador':avaliador
            }, function(response){
            if(response == 0 || response==-1){
                exibirModal("Aviso",response);
                 
            }
           else{
            var respostas= JSON.parse(response);
                for(var i =0; i<respostas.length;i++){  

                    $("input[name='"+respostas[i].pergunta+"'][value='"+respostas[i].resposta+"']").prop("checked",true);
                    $("input[name='"+respostas[i].pergunta+"']").attr('disabled',true);
                 } 
           }           
       });
              $.post('src/php/respostas.php', {
           'acao':'recuperarDataConsenso','avaliado':avaliado, 'avaliador':avaliador, 'tipo':1
            }, function(response){
                $("#data").val(response);        
                $("#data").attr('disabled',true);  
          });

                         
                desabilitaSelect();
                ativarAvaliacao(); // redireciona
                setTimeout(function(){  geraMedia(".consenso-control",tam,0); }, 3000);
               
            }

            else{
                exibirModal("Aviso","Algo deu errado.");                
            }
           
       });

}