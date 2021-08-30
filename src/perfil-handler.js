$("#login-avaliado").submit(function (e) {
    let inputs = $("#login-avaliado :input");
    let valores={
        email: $(inputs[0]).val().toLowerCase().trim(),
        senha: $('#senha-avaliado').val().trim(),
        login: 1,
    };
    $.ajax({
        type: "post",
        url: "src/php/avaliado.handler.php",
        data: valores,
        success: function (r) {           
            if(r==-1){
                exibirModal("Aviso","Crendenciais incorretas!");
            }else if(r==1){
                $(".logado-avaliado").fadeIn();
                $("#log-avaliado").fadeOut();
                $(".login").fadeOut();
                listaEnvioAvaliado();
                listaAvaliador();
            }else{
                alert(r);
                limparSelect();
                resetarAvalicao();
                var ar=r.split("/");
                $(".login").fadeOut();
                
            }
        }
    });
    return false;
});



/*
*   Verifica dados inseridos
*/
$("#form-perfil").submit(function (e,element){
    var input=$("#form-perfil :input");
    var valores={
        nome:$(input[0]).val().toUpperCase().trim(),
        email:$(input[1]).val().toLowerCase().trim(),
        empresa:$(input[2]).val().toUpperCase().trim(),
        nome_aux:$(input[3]).val().toUpperCase().trim(),
        email_aux:$(input[4]).val().toUpperCase().trim(),
        isAvaliador:0,
    };
    $("#form-avaliado option").each(function (index, element) {
        if(element.innerHTML!=". . ."){
            element.remove();
        }else{
            $(element).prop("disabled",false);
            $(element).prop("hidden",false);
            $(element).prop("selected",true);
            $(element).prop("disabled",true);
            $(element).prop("hidden",true);
        }
    });
    $("#form-avaliador option").each(function (index, element) {
        if(element.innerHTML!=". . ."){
            element.remove();
        }else{
            $(element).prop("disabled",false);
            $(element).prop("hidden",false);
            $(element).prop("selected",true);
            $(element).prop("disabled",true);
            $(element).prop("hidden",true);
        }
    });
    desabilitarInput();
    $.ajax({
        type: "post",
        url: "src/php/avaliado.handler.php",
        data: valores,
        success: function (r) {
            resetarAvalicao();
            limparSelect();
            if(r=="semform"){
                //Avaliado sem formulário para responder
                exibirModal("Aviso","Você não tem formulário para responder.\nEntre em contato com o seu gestor imediato para iniciar o processo de avaliação!");
            }else if(r=="Erro de conexão"){
                //Erro de conexão com banco de dados
                exibirModal("ALERTA","Erro de conexão com banco de dados! Contate o suporte");
            }else if(r=="AvaliadorInvalido"){
                exibirModal("ALERTA", "AVALIADOR INVÁLIDO!\nEntre em contato com o suporte!")
            }else{
                var ar=r.split("/");
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
                $(".login").fadeIn();
                $(".logado").fadeOut();
                ativarAvaliacao();
                carregarAvaliacao();
            }

            console.log(r);
        },
    });
    return false;
});

