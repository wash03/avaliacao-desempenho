 function relatorioTopico(setor){

        $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'RelatSetor':1,'setor':setor},
            
            success: function(response){  


            
              $(".row-relat").css('display','block');
               var dataArray = [];
               var data =[];

            for (var i = 0; i < response.length; i++) {
              var cor;
              var conceito;
               if(response[i].media <=25){
                cor  = '#d53342' ;
                conceito = 'D' ;
               }
               else if (response[i].media <= 50) {
                 cor  = '#ffc107' ;
                 conceito = 'C' ;

               }

                else if (response[i].media <= 75) {
                   cor  = '#91e007' ;
                   conceito = 'B' ;

               }
               else{
                   cor  = '#28a745' ;
                   conceito = 'A' ;
               }
                dataArray.push([response[i].area,response[i].media, cor,conceito])  ;
                
                  
            }

              google.charts.load("current", {packages:["corechart"]});
              google.charts.setOnLoadCallback(drawChart);
              var nome= "Conceito Geral dos Requisitos ";
              function drawChart() {
                 var data  = new google.visualization.DataTable();
                 data.addColumn('string', 'Requisito');
                 data.addColumn('number', ' Média');
                 data.addColumn({type: 'string', role:  'style'});
                 data.addColumn({type: 'string', role: 'annotation'});

              
                  if (dataArray.length==14) {
                    data.addRow(dataArray[0]);
                    data.addRow(dataArray[6]);
                    data.addRow(dataArray[7]);
                    data.addRow(dataArray[8]);
                    data.addRow(dataArray[9]);
                    data.addRow(dataArray[10]);
                    data.addRow(dataArray[11]);
                    data.addRow(dataArray[12]);
                    data.addRow(dataArray[13]);
                    data.addRow(dataArray[1]);
                    data.addRow(dataArray[2]);
                    data.addRow(dataArray[3]);
                    data.addRow(dataArray[4]);
                    data.addRow(dataArray[5]);
                    
                  }
                  else{
                    data.addRow(dataArray[0]);
                    data.addRow(dataArray[2]);
                    data.addRow(dataArray[3]);
                    data.addRow(dataArray[4]);
                    data.addRow(dataArray[5]);
                    data.addRow(dataArray[6]);
                    data.addRow(dataArray[7]);
                    data.addRow(dataArray[8]);
                    data.addRow(dataArray[9]);
                    data.addRow(dataArray[1]);

                  }
             

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,                 
                       3,2]);

   
      var options = {
        title: "Conceito Geral dos Requisitos",
         titleTextStyle: {
      color: '#343a40',
      fontName: 'Arial',
      fontSize: 18
    },
        width: 550,
        height: 550,
        bar: {groupWidth: "80%"},
        legend: { position: "none" },
       
        chartArea:{top:50,bottom:30,right:30},


      };
      var chart = new google.visualization.BarChart(document.getElementById("grafico"));
      chart.draw(view, options);
  }
   function getValueAt(column, dataTable, row) {
        return dataTable.getFormattedValue(row, column);
      }
            
                              
                        
            }
            ,
           error: function( error ){

             exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');
             // console.log(error);
              //alert(JSON.stringify(error));

           }

        });

 }

  function conceitoFuncionario(setor){
  if (setor==0){
    $("#grafico-funcionario").css('display','none');
  }
  else{
     $("#grafico-funcionario").css('display','block');
     $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'MedFunc':1,'setor':setor},
            
            success: function(response){  
              if(response=="sem"){
                $("#grafico-funcionario").html(' Indisponível');

                return false;
              }

             var dataArray = [];

            for (var i = 0; i < response.length; i++) {
              var cor;
              var valor= response[i].valor;
              console.log(valor);
              
               if(response[i].conceito  =='D'){
                cor  = '#d53342' ;
               
               
               }
               else if (response[i].conceito  == 'C') {
                 cor  = '#ffc107' ;
                

               }

                else if (response[i].conceito  == 'B') {
                   cor  = '#91e007' ; 
                                  

               }
               else{
                   cor  = '#28a745' ;
                  
                  
               }
                dataArray.push([ response[i].nome ,Number(valor), cor, response[i].conceito])  ;
                
                  
            }
         //console.log(dataArray);
          google.charts.load("current", {packages:["corechart"]});
              google.charts.setOnLoadCallback(drawChart);
              var nome= "Conceito dos Colaboradores ";
              function drawChart() {
                 var data  = new google.visualization.DataTable();
                 data.addColumn('string', 'Requisito');
                 data.addColumn('number', ' Média');
                 data.addColumn({type: 'string', role:  'style'});
                 data.addColumn({type: 'string', role: 'annotation'});

              
                  for (var i = 0; i < dataArray.length; i++) {
                       data.addRow(dataArray[i]);
                  }
                
             

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                      
                       3,2]);

   
      var options = {
        title: "Conceito dos Colaboradores",
        width: 750,
        height: 550,
        bar: {groupWidth: "80%"},
        legend: { position: "none"},
       textStyle: {
          fontName: 'Times-Roman',
          fontSize: 10,
    },
     titleTextStyle: {
      color: '#343a40',
      fontName: 'Arial',
      fontSize: 18
    },
    chartArea:{top:30,width:'35%',height:'50%',bottom:30}
      };
      var chart = new google.visualization.BarChart(document.getElementById("grafico-funcionario"));
      chart.draw(view, options);
  }
   function getValueAt(column, dataTable, row) {
        return dataTable.getFormattedValue(row, column);
      }
                             
                        
            }
            ,
           error: function( error ){
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');

             //console.log(error);

           }

        });
  }

 }




 function porcentagemconceito(){


   $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'Conceitofunc':1},
            
            success: function(response){  
              

               google.charts.load("current", {packages:["corechart"]});
               google.charts.setOnLoadCallback(drawChart);
              function drawChart() {
              var data = google.visualization.arrayToDataTable([
              ['Task', 'Hours per Day'],
              ['A',     response[0].A],
              ['B',      response[1].B],
              ['C',  response[2].C],
              ['D', response[3].D],
      
              ]);

              var options = {
              title: 'Percentual de conceito de não gerentes',
              pieHole: 0.4,
              slices: {
            0: { color: '#28a745' },
            1: { color: '#91e007' },
            2: { color: '#ffc107' },
            3: { color: '#d53342' },
          },
          titleTextStyle: {
      color: '#343a40',
      fontName: 'Arial',
      fontSize: 15
    },
          chartArea:{top:30,bottom:10,right:40,left:100}
              };

              var chart = new google.visualization.PieChart(document.getElementById('conceito-func'));
              chart.draw(data, options);
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


  function porcetnagemConceitoGerente(){
     $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'ConceitoGerente':1},
            
            success: function(response){  
              

               google.charts.load("current", {packages:["corechart"]});
               google.charts.setOnLoadCallback(drawChart);
              function drawChart() {
              var data = google.visualization.arrayToDataTable([
              ['Task', 'Hours per Day'],
              ['A',     response[0].A],
              ['B',      response[1].B],
              ['C',  response[2].C],
              ['D', response[3].D],
      
              ]);

              var options = {
              title: 'Percentual de conceito de gerentes',
              pieHole: 0.4,
              slices: {
            0: { color: '#28a745' },
            1: { color: '#91e007' },
            2: { color: '#ffc107' },
            3: { color: '#d53342' },
          },
          titleTextStyle: {
      color: '#343a40',
      fontName: 'Arial',
      fontSize: 15
    },
          chartArea:{top:30,bottom:10,right:40,left:100}
          
              };

              var chart = new google.visualization.PieChart(document.getElementById('conceito-gerente'));
              chart.draw(data, options);
              }                      
                        
          }
            ,
           error: function( error ){
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');  
              console.log(error);    

            //alert(JSON.stringify(error));

           }

  });      
 
}



function requisitoSetor(){
  var requisito =$("#relatorio-req option:selected").val();
  if(!requisito){
    return false;
  }
  else{
    $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'ReqSet':1,'requisito':requisito},
            
            success: function(response){  
              //console.log(response);

               var dataArray = [];

            for (var i = 0; i < response.length; i++) {
              var cor;
              var conceito;
              if(response[i].media ==0){
                cor  = '#d53342' ;
                conceito = 'Sem' ;
               }
              
              else if(response[i].media <=25){
                cor  = '#d53342' ;
                conceito = 'D' ;
               }
               else if (response[i].media <= 50) {
                 cor  = '#ffc107' ;
                 conceito = 'C' ;

               }

                else if (response[i].media <= 75) {
                   cor  = '#91e007' ;
                   conceito = 'B' ;

               }
               else{
                   cor  = '#28a745' ;
                   conceito = 'A' ;
               }
                dataArray.push([response[i].setor,response[i].media, cor, conceito]);             
                  
            }


              google.charts.load("current", {packages:["corechart"]});
              google.charts.setOnLoadCallback(drawChart);
              var nome= "Conceito do Requisito por Áreas";
              function drawChart() {
                 var data  = new google.visualization.DataTable();
                 data.addColumn('string', 'Requisito');
                 data.addColumn('number', ' Média');
                 data.addColumn({type: 'string', role:  'style'});
                 data.addColumn({type: 'string', role: 'annotation'});

              
                  for (var i = 0; i < dataArray.length; i++) {
                       data.addRow(dataArray[i]);
                  }
                
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,3,2]);
   
           var options = {
             title: "Conceito do Requisito por Áreas",
             width: 750,
             height: 450,
             bar: {groupWidth: "85%"},
             legend: { position: "none" },
              chartArea:{top:30,width:'45%',height:'65%',bottom:30},

          };
          var chart = new google.visualization.BarChart(document.getElementById("grafico-req"));
          chart.draw(view, options);
           
      }                                         
                        
          }
            ,
           error: function( error ){
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');      

           }

  });
  }


}





function conceitoSetores(){
  $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'ConSet':1},
            
            success: function(response){  
           

               var dataArray = [];

            for (var i = 0; i < response.length; i++) {
              var cor;
              var valor;
              
              if(response[i].media ==0){
                cor  = '#d53342' ;
                valor="Sem";
                
               }
              else if(response[i].media <=25){
                cor  = '#d53342' ;
                valor  = "D";
               
               }
               else if (response[i].media  <= 50) {
                 cor  = '#ffc107' ;
                 valor  = "C";

               }

                else if (response[i].media  <=75 ) {
                   cor  = '#91e007' ; 
                   valor  = "B";               

               }
               else{
                   cor  = '#28a745' ;
                   valor  = 'A';
                  
               }
                dataArray.push([response[i].setor,response[i].media, cor,valor] );             
                  
            }


              google.charts.load("current", {packages:["corechart"]});
              google.charts.setOnLoadCallback(drawChart);
              var nome= "Conceito das Áreas ";
              function drawChart() {
                 var data  = new google.visualization.DataTable();
                 data.addColumn('string', 'Área');
                 data.addColumn('number', ' Média');
                 data.addColumn({type: 'string', role:  'style'});
                 data.addColumn({type: 'string', role: 'annotation'});

              
                  for (var i = 0; i < dataArray.length; i++) {
                       data.addRow(dataArray[i]);
                  }
                
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,2,3]);
   
           var options = {
             title: "Conceito Geral das Áreas",
             width: 750,
             height: 600,
             bar: {groupWidth: "80%"},
             legend: { position: "none" },
             chartArea:{top:50,width:'45%',bottom:70},
          };
          var chart = new google.visualization.BarChart(document.getElementById("grafico-conceitos"));
          chart.draw(view, options);
           
      } 
                                                                              
          }
            ,
           error: function( error ){
              exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');      
              //alert(error);
           }

  });

}


 function topicoNãoGerente(){

        $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'RelatReqNãoG':1},
            
            success: function(response){  


            
              $(".row-relat").css('display','block');
               var dataArray = [];
               var data =[];

            for (var i = 0; i < response.length; i++) {
              var cor;
              var conceito;
               if(response[i].media <=25){
                cor  = '#d53342' ;
                conceito = 'D' ;
               }
               else if (response[i].media <= 50) {
                 cor  = '#ffc107' ;
                 conceito = 'C' ;

               }

                else if (response[i].media <= 75) {
                   cor  = '#91e007' ;
                   conceito = 'B' ;

               }
               else{
                   cor  = '#28a745' ;
                   conceito = 'A' ;
               }
                dataArray.push([response[i].area,response[i].media, cor,conceito])  ;
                
                  
            }

              google.charts.load("current", {packages:["corechart"]});
              google.charts.setOnLoadCallback(drawChart);
              var nome= "Conceito dos Requisitos ";
              function drawChart() {
                 var data  = new google.visualization.DataTable();
                 data.addColumn('string', 'Requisito');
                 data.addColumn('number', ' Média');
                 data.addColumn({type: 'string', role:  'style'});
                 data.addColumn({type: 'string', role: 'annotation'});

              
                  if (dataArray.length==14) {
                    data.addRow(dataArray[0]);
                    data.addRow(dataArray[6]);
                    data.addRow(dataArray[7]);
                    data.addRow(dataArray[8]);
                    data.addRow(dataArray[9]);
                    data.addRow(dataArray[10]);
                    data.addRow(dataArray[11]);
                    data.addRow(dataArray[12]);
                    data.addRow(dataArray[13]);
                    data.addRow(dataArray[1]);
                    data.addRow(dataArray[2]);
                    data.addRow(dataArray[3]);
                    data.addRow(dataArray[4]);
                    data.addRow(dataArray[5]);
                    
                  }
                  else{
                    data.addRow(dataArray[0]);
                    data.addRow(dataArray[2]);
                    data.addRow(dataArray[3]);
                    data.addRow(dataArray[4]);
                    data.addRow(dataArray[5]);
                    data.addRow(dataArray[6]);
                    data.addRow(dataArray[7]);
                    data.addRow(dataArray[8]);
                    data.addRow(dataArray[9]);
                    data.addRow(dataArray[1]);

                  }
             

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                      
                       3,2]);

   
      var options = {
        title: "Conceito dos Requisitos de não gerentes",
        width: 470,
        height: 550,
        bar: {groupWidth: "60%"},
        legend: { position: "left" },
     

        chartArea:{top:50,width:'55%',height:'80%',left:80,bottom:30}
      };
      var chart = new google.visualization.BarChart(document.getElementById("req-func"));
      chart.draw(view, options);
  }
   function getValueAt(column, dataTable, row) {
        return dataTable.getFormattedValue(row, column);
      }
            
                              
                        
            }
            ,
           error: function( error ){

             exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');
             // console.log(error);
              //alert(JSON.stringify(error));

           }

        });

 }


  function topicoGerente(){

        $.ajax({
            url:"src/php/relatorio.handler.php",
            type:"POST",
            data: {'RelatReqGerente':1},
            
            success: function(response){  


            
              $(".row-relat").css('display','block');
               var dataArray = [];
               var data =[];

            for (var i = 0; i < response.length; i++) {
              var cor;
              var conceito;
               if(response[i].media <=25){
                cor  = '#d53342' ;
                conceito = 'D' ;
               }
               else if (response[i].media <= 50) {
                 cor  = '#ffc107' ;
                 conceito = 'C' ;

               }

                else if (response[i].media <= 75) {
                   cor  = '#91e007' ;
                   conceito = 'B' ;

               }
               else{
                   cor  = '#28a745' ;
                   conceito = 'A' ;
               }
                dataArray.push([response[i].area,response[i].media, cor,conceito])  ;
                
                  
            }

              google.charts.load("current", {packages:["corechart"]});
              google.charts.setOnLoadCallback(drawChart);
              var nome= "Conceito dos Requisitos ";
              function drawChart() {
                 var data  = new google.visualization.DataTable();
                 data.addColumn('string', 'Requisito');
                 data.addColumn('number', ' Média');
                 data.addColumn({type: 'string', role:  'style'});
                 data.addColumn({type: 'string', role: 'annotation'});

              
                  if (dataArray.length==14) {
                    data.addRow(dataArray[0]);
                    data.addRow(dataArray[6]);
                    data.addRow(dataArray[7]);
                    data.addRow(dataArray[8]);
                    data.addRow(dataArray[9]);
                    data.addRow(dataArray[10]);
                    data.addRow(dataArray[11]);
                    data.addRow(dataArray[12]);
                    data.addRow(dataArray[13]);
                    data.addRow(dataArray[1]);
                    data.addRow(dataArray[2]);
                    data.addRow(dataArray[3]);
                    data.addRow(dataArray[4]);
                    data.addRow(dataArray[5]);
                    
                  }
                  else{
                    data.addRow(dataArray[0]);
                    data.addRow(dataArray[2]);
                    data.addRow(dataArray[3]);
                    data.addRow(dataArray[4]);
                    data.addRow(dataArray[5]);
                    data.addRow(dataArray[6]);
                    data.addRow(dataArray[7]);
                    data.addRow(dataArray[8]);
                    data.addRow(dataArray[9]);
                    data.addRow(dataArray[1]);

                  }
             

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                  3,2]);

   
      var options = {
        title: "Conceito dos Requisitos de Gerentes",
        width: 390,
        height: 550,
        bar: {groupWidth: "70%"},
        legend: { position: "left" },
     

        chartArea:{top:50,width:'55%',height:'80%',right:30,bottom:30}
      };
      var chart = new google.visualization.BarChart(document.getElementById("req-gerente"));
      chart.draw(view, options);
  }
   function getValueAt(column, dataTable, row) {
        return dataTable.getFormattedValue(row, column);
      }
            
                              
                        
            }
            ,
           error: function( error ){

             exibirModal("INFORMATIVO","Algo deu errado");
              $(".row-relat").css('display','none');
             // console.log(error);
              //alert(JSON.stringify(error));

           }

        });

 }

