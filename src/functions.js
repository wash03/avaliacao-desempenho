// mantem a div logado após recarregar a página
  $.post('src/php/carregaAvaliacao.php', {
           'acao':'tipo'
       }, function(response){

            if(response==2){
               $(".logado").fadeIn();
                $(".login").fadeOut();

            }
            if(response==1){
                $(".logado-avaliado").fadeIn();
                $("#log-avaliado").fadeOut();
            }
           
           
       });

  

// seleciona o tipo de resposta que aparece no formulário
$("#btnform").click(function(){
    var avaliado =$("#form-avaliado option:selected").val();
    var avaliador=$("#form-avaliador option:selected").val();
    var tipo=$("#form-tipo option:selected").val();
    var data=$("#data").val();

    $("#media-consenso").css("display","none");

    if(!avaliado || !avaliador || !tipo || !data){
        exibirModal("Aviso","Preencha todos os campos!");
        return false;
    }  
   
    else{

        $.post('src/php/carregaAvaliacao.php', {
           'avaliado':avaliado,'avaliador':avaliador,'tipo':tipo
       }, function(response){

        
            if(response==1){
                desabilitaSelect();
                carregarAvaliacao(1,0);
                

            }
            else if(response==2){
                  desabilitaSelect();
                carregarAvaliacao(2,0);
              

            }
            else if(response==3){
                desabilitaSelect();
                carregarAvaliacao(3,0);
                
            }
            else if(response==6){
                desabilitaSelect();
                carregarAvaliacao(3,1);
                

            }
            else if(response==7){
                desabilitaSelect();
                carregarAvaliacao(2,1);
                
            }
            else if (response==8){
                desabilitaSelect();
                carregarAvaliacao(1,1);
                
               
                
            }
      
            else if (response==0){
                exibirModal("Aviso","Não há formulário para ser respondido.");
                
            }
      
            else if(response==4){
                exibirModal("Aviso","Você está logado como avaliador.");
                
            }
            else if(response==5){
                exibirModal("Aviso","Você está logado como avaliado.");
            }

            else{
                exibirModal("Aviso","Algo deu errado." );
                
            }
           
       });
    } 
    
});



function desabilitaSelect(){
    $("#form-avaliado").attr('disabled',true);
    $("#form-avaliador").attr('disabled',true);
    $("#form-tipo").attr('disabled',true);
    
}
function verificaNota(nota, notaNode) {
    if(nota>=76){
        $(notaNode).addClass("nota-A");
    }else if(nota<=75 && nota>=51){
        $(notaNode).addClass("nota-B");
    }else if(nota<=50 && nota>=26){
        $(notaNode).addClass("nota-C");
    }else{
        $(notaNode).addClass("nota-D");
    }
}
function LimpaRelatorioIndividual() {
    /**
     * Inicializa variáveis com nome das classes
     */
    const notaA = "nota-A";
    const notaB = "nota-B";
    const notaC = "nota-C";
    const notaD = "nota-D";
    let item = "";
    /**
     * Limpa os valores do cabeçalho do relatório
     */
    $("#rel-indiv-avaliador").html("");
    $("#rel-indiv-setor").html("");
    $("#rel-indiv-cargo").html("");
    $("#rel-indiv-data").html("");
    /**
     * Verifica cada item e efetua a limpeza do conteúdo
     */
    for (let index = 0; index < 4; index++) {
        if (index == 0) {
            item=".primeiro-item";
        }else if(index == 1){
            item=".segundo-item";
        }else if(index == 2){
            item=".terceiro-item";
        }else{
            item=".quarto-item";
        }
        if($(item + " .nota-geral").hasClass(notaA)){
            $(item + " .nota-geral").removeClass(notaA);
        }else if($(item + " .nota-geral").hasClass(notaB)){
            $(item + " .nota-geral").removeClass(notaB);
        }else if($(item + " .nota-geral").hasClass(notaC)){
            $(item + " .nota-geral").removeClass(notaC);
        }else if($(item + " .nota-geral").hasClass(notaD)){
            $(item + " .nota-geral").removeClass(notaD);
        }
        $(item + " .nota-geral").html("");
        $(item + " .row").each(function (index, element) {
            if(index!=0){
                $(this).remove();
            }
            
        });
    }
}
function inserirRespostas(dados, ondeInserir) {
    const rowNode = document.createElement("div");
    const labelNode = document.createElement("div");
    const notaNode = document.createElement("div");
    const nota = parseInt(dados.resposta)*25;
    /**
     * Adiciona as classes iniciais e margin na ROW
     */
    $(rowNode).addClass("row");
    $(rowNode).css("margin-bottom","10px");
    $(labelNode).addClass("col-md-10");
    $(notaNode).addClass("card col-md-2");
    /**
     * Adiciona os valores das notas
     */
    $(notaNode).html("Nota: "+nota);
    $(labelNode).html(dados.label);
    /**
     * Verifica a nota e adiciona classe nota-A||B||C||D de acordo com valor
     */
    verificaNota(nota,notaNode);
    /**
     * Adiciona cada elemento no corpo HTML
     */
    $(rowNode).append(labelNode);
    $(rowNode).append(notaNode);
    $(ondeInserir).append(rowNode);
}

function carregaRelatorioIndividual(selectVal) {
    const valores = {
        avaliado: selectVal,
        individual: 1,
    }
    $.ajax({
        type: "post",
        url: "src/php/relatorio.handler.php",
        data: valores,
        success: function (r) {
            if (r=="semdados") {
                exibirModal("Erro","Erro de consulta no banco de dados!");
            } else {
                LimpaRelatorioIndividual();
                const arr = r.split("/");
                const avaliador = arr[0];
                const dados = JSON.parse(arr[1]);
                const respostas = JSON.parse(dados.respostas_consenso);
                let notaGeral = 0;
                let notaFinal = 0;
                let ondeInserir = "";
                $("#rel-indiv-avaliador").html(avaliador);
                $("#rel-indiv-setor").html(dados.setor_avaliado);
                $("#rel-indiv-cargo").html(dados.cargo_avaliado);
                $("#rel-indiv-data").html(dados.data_consenso);
                respostas.forEach(function(element,index){
                    if (index<=1) {
                        ondeInserir = ".primeiro-item";
                    }else if(index>1 && index<=6 && ondeInserir != ".segundo-item"){
                        notaGeral=Math.floor(notaGeral/2);
                        $($(ondeInserir).find(".nota-geral")).html("Nota: "+notaGeral);
                        verificaNota(notaGeral,$($(ondeInserir).find(".nota-geral")));
                        notaFinal+=notaGeral;
                        ondeInserir = ".segundo-item";
                        notaGeral=0;
                    }else if(index>6 && index<=9 && ondeInserir != ".terceiro-item"){
                        notaGeral=Math.floor(notaGeral/5);
                        $($(ondeInserir).find(".nota-geral")).html("Nota: "+notaGeral);
                        verificaNota(notaGeral,$($(ondeInserir).find(".nota-geral")));
                        notaFinal+=notaGeral;
                        ondeInserir = ".terceiro-item";
                        notaGeral=0;
                    }else if(index>9 && ondeInserir != ".quarto-item"){
                        notaGeral=Math.floor(notaGeral/3);
                        $($(ondeInserir).find(".nota-geral")).html("Nota: "+notaGeral);
                        verificaNota(notaGeral,$($(ondeInserir).find(".nota-geral")));
                        notaFinal+=notaGeral;
                        ondeInserir = ".quarto-item";
                        notaGeral=0;
                    }
                    notaGeral+=parseInt(element.resposta)*25;
                    inserirRespostas(element,ondeInserir);
                    if(index==13){
                        notaGeral=notaGeral/4;
                        $($(ondeInserir).find(".nota-geral")).html("Nota: "+notaGeral);
                        verificaNota(notaGeral,$($(ondeInserir).find(".nota-geral")));
                        notaFinal+=notaGeral;
                    }
                });
                if (dados.isGerencial){
                    $(".quarto-item").show();
                    notaFinal/=4;
                }else{
                    $(".quarto-item").hide();
                    notaFinal/=3;
                }
                verificaNota(notaFinal,$(".nota-final").find(".col-md-2"));
                $($(".nota-final").find(".col-md-2")).html(notaFinal);
                $(".rel-indiv-itens").show();
                $(".footer-assinatura").show();
                $(".rel-indiv-indicadores").show();
            }
        }
    });
}


function dateDiff(d1, d2) {
    if(d1===undefined){
        d1 = new Date();
    }else{
        d1 = new Date(Date.parse(d1));
    }
    if(d2===undefined){
        d2 = new Date();
    }else{
        d2 = new Date(Date.parse(d2));
    }
    if (d1>d2) {
        return Math.ceil((d1-d2)/(1000*60*60*24));
    } else {
        return Math.ceil((d2-d1)/(1000*60*60*24));
    }
}

//ATIVA A GUIA DA AVALIAÇÃO
function ativarAvaliacao() {
    $("#v-pills-perfil-tab").removeClass("active show");
    $("#v-pills-form-tab").removeClass("active show");
    $("#v-pills-form-tab").attr('aria-selected','true');
    $("#v-pills-perfil").removeClass("active show");
    $("#v-pills-form").removeClass("active show");
    $("#v-pills-admin").removeClass("active show");
    $("#v-pills-admin-tab").removeClass("active show");   
    $("#v-pills-form").toggleClass("active show");
    $("#v-pills-form-tab").toggleClass("active show");
    $("#v-pills-relatorios-tab").removeClass("active show");
    $("#v-pills-relatorios").removeClass("active show");
}

//ADICIONA UMA OPÇÃO PARA UM SELECT

function addOption(element,text, value, isSelected=false){
    //#form-avaliado
    //#form-avaliador
    let opt = new Option(text,value,isSelected,isSelected);
    $(opt).html(text);
    $(element).append(opt);
}



//LIMPA OS DADOS DO FORMULÁRIO DE AVALIAÇÃO
function limparSelect() {
    $("#form-avaliado option").each(function (index, element) {
        $(element).remove();
    });
    $("#form-avaliador option").each(function (index, element) {
        $(element).remove();
        
    });

}

function resetarAvalicao() {
    //avaliado
    $(".avaliacao :input[type='radio']").prop("checked",false);
  
    //$("#form-avaliacao :input[type='radio']").att("disabled",false);
    esconderInput(".avaliado-control");
    esconderInput(".avaliador-control");
    esconderInput(".consenso-control");
    $(".avaliacao").hide();


}
//LIMPA OS DADOS DO FORMULÁRIO DO PERFIL

function resetarPerfil() {
    $("#form-perfil :input").each(function (index, element) {
        element.val("");
    });
}
//EXIBE ALERTAS CUSTOMIZADOS ATRAVÉS DA MODAL

function exibirModal(titulo = String, conteudo = String){
    
    $("#exampleModalLabel").html(titulo);
    $(".modal-body").html(conteudo);
    $("#exampleModal").modal();
    
}
//DESABILITA OS INPUTS DO FORMULÁRIO DE AVALIAÇÃO

function desabilitarInput() {
    let tamanho=$("#form-avaliacao :input").length;
    $("#form-avaliacao :input[type=radio]").each(function (index, element) {
        $(element).prop('disabled',true);
    });
}

//HABILITAR OS INPUTS DE UMA DETERMINADA CLASSE
function habilitarInput(inputclass = String) {
    if(inputclass!=""){
        $("#form-avaliacao "+ inputclass +" :input").each(function(index, element) {
            $(element).prop('disabled',false);
        });
    }
}

function exibirInput(inputclass = String) {
    if(inputclass!=""){
        $(inputclass).each(function(index, element) {
            $(element).show();
        });
    }
}

function esconderInput(inputclass = String) {
    if(inputclass!=""){
        $(inputclass).each(function(index, element) {
            $(element).hide();
        });
    }
}
function exibirForm(){
    $(".avaliacao").show();

}
function capturarDados(classe = String) {
    var valores = new Array();
    var n = 0;
    var name = "aloha";
    //Lê dados do formulário e captura label dos tópicos
    $(".label-pergunta").each(function(index,element){
        valores[index]={};
        valores[index]["label"]=$(element).html();
    });
    $(classe+" :input").each(function(index,element){ 
        if(name!=$(element).prop('name')){
            name=$(element).prop('name');
            valores[n]["name"]=name;
            valores[n]["resposta"]=$(classe+" :input[name='"+name+"']:checked").val();
            n++;
        }
    });
    return JSON.stringify(valores);
}

function salvarDados(classe = String,tamanho) {
    var valores = new Object();
    var tipo = $("#form-tipo").val();
    var avaliado = new String($("#form-avaliado").val());
    var avaliador = new String($("#form-avaliador").val());
    var data = new String($("#data").val());
    valores = capturarDados(classe);
    $.ajax({
        type: "post",
        url: "src/php/avaliacao.handler.php",
        data: "&data="+data+"&avaliado="+avaliado+"&avaliador="+avaliador+"&tipo="+tipo+"&dados="+valores+"&tamanho="+tamanho,
        success: function (response) {
            if(response==1){
                exibirModal("INFORMATIVO","Dados salvos com sucesso!");
                
            }
            else{exibirModal("INFORMATIVO","Algo deu errado");}
           
        }
    });
}

function salvarMedia(conceito) {
        var acao="salvarMedia";
    var avaliado = new String($("#form-avaliado").val());
    var avaliador = new String($("#form-avaliador").val());

    
      $.ajax({
        type: "post",
        url: "src/php/respostas.php",
        data: "&acao="+acao+"&avaliado="+avaliado+"&avaliador="+avaliador+"&conceito="+conceito,
        success: function (response) {
          
                  
        }
    });

}

function resetSlideForm() {
    var form = $('#form-avaliacao');
    slides = form.find(".slideform-btn");
    form.find(".active").each(function(index,element){
        $(element).removeClass("active");
    });
    if (slides.length>0) {
        $(form.find(".slideform-btn")).each(function(index, element){
            element.remove();
        });   
    }
}

function initSlideForm(isGerencial = null) {
    var form = $('#form-avaliacao');
    nroSlides = form.find(".slideform-slide");
    resetSlideForm();
    if (isGerencial == 0 || isGerencial == null) {
        lideranca=nroSlides.splice(nroSlides.length-1,1);
        $(lideranca).find("input").prop("disabled",true);
    }
    form.slideform({
        slides: nroSlides,
        submit: function (event, form) {
            form.trigger('goForward');
        }
    });
}


function carregarDados(dados = String) {
    dados = JSON.parse(dados);
    dados.forEach(function(e){
        $(".avaliacao :input[name='"+e.name+"'][value='"+e.resposta+"']").prop('checked',true);
    });
}


function geraMedia(classe = String,tam,salva){

    var tipo = $("#form-tipo option:selected").val();
    var valores = new Object();
    var acao ="gerarMedia";
    var avaliado = new String($("#form-avaliado").val());
    var avaliador = new String($("#form-avaliador").val());
    var tamanho = tam;
    valores = capturarDados(classe);
      
      $.ajax({
        type: "post",
        url: "src/php/respostas.php",
        data: "&acao="+acao+"&avaliado="+avaliado+"&avaliador="+avaliador+"&tipo="+tipo+"&dados="+valores+"&tamanho="+tamanho,
        success: function (response) {
           var conceito=JSON.parse(response);          
            
            $(".media-consenso").fadeIn();
            

              if(salva==1){
                salvarMedia(conceito[0].letra);
                
            }


            if(conceito[0].letra=='A'){
                $("#mediaA").fadeIn();
                $("#mediaB").css('display','none');
                $("#mediaC").css('display','none');
                $("#mediaD").css('display','none');

            }
            else if(conceito[0].letra=='B'){
                $("#mediaB").fadeIn();
                $("#mediaA").css('display','none');
                $("#mediaC").css('display','none');
                $("#mediaD").css('display','none');
                

            }
            else if(conceito[0].letra=='C'){
                $("#mediaC").fadeIn();
                $("#mediaA").css('display','none');
                $("#mediaB").css('display','none');
                $("#mediaD").css('display','none');
               

            }
            else if(conceito[0].letra=='D'){
                $("#mediaD").fadeIn(); 
                $("#mediaA").css('display','none'); 
                $("#mediaB").css('display','none');  
                $("#mediaC").css('display','none');         

            }
            else{
                $(".media-consenso").html("Erro");
            } 
          
        }
    });

}


//CARREGA DADOS DE FORMULÁRIO
function carregarAvaliacao(tipo,Eavaliador) {
    var avaliado =$("#form-avaliado option:selected").val();
    var avaliador=$("#form-avaliador option:selected").val();

    if(Eavaliador==1){
        $("#lideranca").css("display","block");
    }
    else{
         $("#lideranca").css("display","none");
    }
    if(tipo==3){
        exibirInput(".avaliado-control");
        $(".avaliador-control").css("display","none");
        $(".consenso-control").css("display","none");
        exibirForm();
    }
    else if(tipo==2){
        exibirInput(".avaliador-control");
        $(".consenso-control").css("display","none");
        $(".avaliado-control").css("display","none");
        exibirForm();
    }
    else if(tipo==1){
        var avaliado =$("#form-avaliado option:selected").val();
        var avaliador=$("#form-avaliador option:selected").val();
        var tipo=$("#form-tipo option:selected").val();
        

         $.post('src/php/respostas.php', {
           'acao':'preencheRespostaAvaliado','avaliado':avaliado, 'avaliador':avaliador
       }, function(response){

            if(response == 0 || response==-1){
                exibirModal("Aviso","Algo deu errado na exibição das respostas do avaliado ou o formulário não foi respondido.");
                fechaForm();
                return false;
                 
            }
           else{
           
            var respostas= JSON.parse(response);
            
                for(var i =0; i<respostas.length;i++){  

                    $("input[name='"+respostas[i].pergunta+"'][value='"+respostas[i].resposta+"']").prop("checked",true);
                    $("input[name='"+respostas[i].pergunta+"']").attr('disabled',true);
                 } 
                  $.post('src/php/respostas.php', {
           'acao':'preencheRespostaAvaliador','avaliado':avaliado, 'avaliador':avaliador
       }, function(response){
            if(response == 0 || response==-1){
                exibirModal("Aviso","Algo deu errado na exibição das respostas do avaliador ou o formulário não foi respondido.");
               fechaForm();
                return false;
                 
            }
           else{
            var respostas= JSON.parse(response);
           
                for(var i =0; i<respostas.length;i++){  

                    $("input[name='"+respostas[i].pergunta+"'][value='"+respostas[i].resposta+"']").prop("checked",true);
                    $("input[name='"+respostas[i].pergunta+"']").attr('disabled',true);
                 }
            exibirInput(".consenso-control");
            exibirInput(".avaliado-control");
            exibirInput(".avaliador-control");
            exibirForm(); 
                 
           }
    
           
             });
                
           }
    
           
       });
        
    }

    else{
        exibirModal("Aviso","Algo deu errado.");
        
    }
}

function validarCPF(elementID = null) {
    document.getElementById(elementID).addEventListener("keydown",(function (e){
        if(($(this).prop("type")=="number") && ((e.key=="-")||(e.key==",")||(e.key==".")||(e.key=="+"))){
            e.preventDefault();
            return false;
        }else if(!(isNaN(parseInt(e.key))) && (this.value=="")){
            console.log("É CPF");
            $(this).prop("type","number");
        }else if(isNaN(parseInt(e.key)) && (this.value=="")){
            console.log("É email");
            $(this).prop("type","email");
        }
    }));   
}
$(document).ready(function () {
   // validarCPF("email");
    //validarCPF("login_email");
    //validarCPF("processo_email");
});

function limpaForm(){
    $('.avaliador-control').attr('checked',false);
    $('.avaliado-control').attr('checked',false);
    $('.consenso-control').attr('checked',false);
    
}

// fecha o formuláeio após envio
 function fechaForm(){

    //$(".avaliacao").css("display","none");
   // $(".consenso-control").css("display","none");
   // $(".avaliador-control").css("display","none");
   // $(".avaliado-control").css("display","none");
    $(".avaliador-control :input[type='radio']").attr('disabled',false);
    $(".avaliador-control :input[type='radio']").attr('disabled',false);
    $(".consenso-control :input[type='radio']").attr('disabled',false);

    $("#form-avaliado").attr('disabled',false);
    $("#form-avaliador").attr('disabled',false);
    $("#form-tipo").attr('disabled',false);
    $("#data").attr('disabled',false);
    $(".media-consenso").css("display","none");
    resetarAvalicao();
    listaEnvioAvaliado();
    listaAvaliador();
 }


function encerraSessao(){
    
      $.post('src/php/avaliador.handler.php', {
           'acao':'sair'
       }, function(response){
            if(response=="Não logado"){
                alert(response);
            }
            else if(response=="saiu"){
                window.location.reload();

            }
            else{
                alert("Algo deu errado.");
            }

            
                   
       });

}

// conta quantos formulários estão em aberto para o avaliado
 function contaFormulario(){
    
    
    $.post('src/php/avaliado.handler.php', {
           'acao':'contagem'
       }, function(response){
        
        var numero=JSON.parse(response);

        
             exibirModal("INFORMATIVO","Você tem: "+  numero[0].av +" formulário(s) de avaliado e "+ numero[0].consenso +" formulário(s) de consenso.");
            
                   
    });

 }


function mudaSenhaAvaliado(){
    $(".alterar-senha-avaliado").fadeIn();
    
    
 }

 $("#alterar-senha-avaliado").submit(function (e) { 
    let inputs = $("#alterar-senha-avaliado :input");
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
        url: "src/php/avaliado.handler.php",
        data: valores,
        success: function (response) {
            if(response==2){
                exibirModal("Aviso","Você não está logado!");
            } else if(response == 1){
                exibirModal("Aviso","Senha Alterada com sucesso!");
                $(".alterar-senha-avaliado").fadeOut();
                $(inputs[0]).val("");
                $(inputs[1]).val("");
            }
        }
    });
    }
   
    return false;
});



