function carregarDadosConsenso() {
    $.ajax({
        type: "post",
        url: "src/php/reports.handler.php",
        data: "data",
        success: function (r) {
            
        }
    });
}



    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["Copper", 8.94, "#b87333"],
        ["Silver", 10.49, "silver"],
        ["Gold", 19.30, "gold"],
        ["Platinum", 21.45, "#e5e4e2"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Density of Precious Metals, in g/cm^3",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }


   function getValueAt(column, dataTable, row) {
        return dataTable.getFormattedValue(row, column);
      }
      
    $("#login-relat").submit(function (e) {
    let inputs = $("#login-relat :input");
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
