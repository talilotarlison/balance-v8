<script type="text/javascript">
    /**
     * Gráfico PieChart com JavaScript em uso no sistema.
     * Autor: Stack Overflow Community
     * Data: Referências coletadas em 2023
     * 
     * Referências:
     * - https://pt.stackoverflow.com/questions/274954/como-montar-gr%c3%a1fico-com-google-charts-e-php
     * - https://pt.stackoverflow.com/questions/181193/retirar-fundo-do-google-chart
     * - https://pt.stackoverflow.com/questions/117869/google-chart-bar-chart-mudar-o-plano-de-fundo-background
     **/
    google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Movimetação de Caixa'],
          ['Entradas',  <?php echo $_SESSION['entrada'] ; ?>],
          ['Saidas',    <?php echo abs($_SESSION['saida']) ?>],
          ['Agendados', <?php echo isset($_SESSION['agendado']) ? abs($_SESSION['agendado']):0; ?>]
        ]);
        
        var options = {
          title: 'Movimetação de Caixa',
          backgroundColor: 'transparent',
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
</script>

