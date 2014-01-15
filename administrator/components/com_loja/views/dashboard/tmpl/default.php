<?php 

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'libs'.DS.'highroller'.DS.'HighRoller.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'libs'.DS.'highroller'.DS.'HighRollerSeriesData.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'libs'.DS.'highroller'.DS.'HighRollerLineChart.php');
//require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'PagSeguroLibrary'.DS.'PagSeguroLibrary.php');

$chartData = array(1, 3, 2, 0, 1, 4);

$linechart = new HighRollerLineChart();
$linechart->chart->renderTo = 'linechart';
$linechart->title->text = 'Pedidos realizados';
$linechart->yAxis->title->text = 'Numero de pedidos';

$series1 = new HighRollerSeriesData();
$series1->addName('Datas')->addData($chartData);

$linechart->addSeries($series1);
 ?>
 
 <?php echo HighRoller::setHighChartsLocation('components'.DS.'com_loja'.DS.'libs'.DS.'highcharts'.DS.'highcharts.js');?>
<h3>Bem vindo ao seu painel de controle!</h3>
<h4>Aqui você poderá visualizar e modificar as informações referentes a seu site. </h4>

<div class="">
<div id="linechart" style="width: 400px; height: 350px;"></div>

<script type="text/javascript">
  
</script>
<script type="text/javascript">

$(function () {
	   <?php echo $linechart->renderChart('linechart');?>
});
    
</script>
<div class="">
Ultimas vendas realizadas
</div>