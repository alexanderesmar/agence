
<?php 
if (isset($res)) {
	var_dump($res);

	echo "<br><br><br><br><br><br><br><br><br><br><br>";
}
ob_start();
?>

<div id="show_relatorio" ng-app="myApp" ng-controller="customersCtrl">



<br>
<table class="greyGridTable">
<thead>
<tr>
<td colspan="5" >  </td>
</tr>
<tr>
<th>Período</th>
<th>Receita Líquida</th>
<th>Custo Fixo</th>
<th>Comissão</th>
<th>Lucro</th>
</tr>
</thead>
<tbody>
<tr>
<td></td>
<td>cell2_1</td>
<td>cell3_1</td>
<td>cell4_1</td>
<td>cell5_1</td>
</tr>
</tbody>
<tfoot>
<tr>
<td>Saldo</td>
<td>foot2</td>
<td>foot3</td>
<td>foot4</td>
<td>foot5</td>
</tr>
</tfoot>
</table> 
  
</div>

<?php
$table_relatorio = ob_get_contents();
ob_end_clean ();
?>

<?php echo $table_relatorio; ?>
