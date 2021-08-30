<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current');   // Don't need to specify chart libraries!
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        var wrapper = new google.visualization.ChartWrapper({
          chartType: 'ColumnChart',
          dataTable: [['', 'Germany', 'USA', 'Brazil', 'Canada', 'France', 'RU'],
                      ['', 700, 300, 400, 500, 600, 800]],
          options: {'title': 'Countries'},
          containerId: 'vis_div'
        });
        wrapper.draw();
      }


    </script>
    <br><br><br>
    <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    var nome= "Density of Precious Metals, in g/cm^3";
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['Element', 'Density', { role: 'style' }, { role: 'annotation' } ],
        ['Copper', 8.94, '#b87333','#A'],
        ['Silver', 10.49, 'silver','#B'],
        ['Gold', 19.30, 'gold','#C'],
        ['Platinum', 21.45, '#e5e4e2','#D']
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




      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Work',     11],
          ['Eat',      2],
          ['Commute',  2],
          ['Watch TV', 2],
          ['Sleep',    7]
        ]);

        var options = {
          title: 'My Daily Activities',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
  </script>
<div id="barchart_values" style="width: 900px; height: 300px;"></div>

<br><br><br>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="vis_div" style="width: 600px; height: 400px;"></div>

    <br><br><br>
    <div id="donutchart" style="width: 900px; height: 500px;"></div>
  </body>
</html>

