
<?php
if (isset($GLOBALS["res"])) {
  $res=$GLOBALS["res"];



?>

<div id="show_relatorio">

<?php foreach ($res as $iterador) { 

$t1=0;
$t2=0;
$t3=0;
$t4=0;

  ?>
<br>
<table class="greyGridTable">
<thead>
<tr>
<td colspan="5" > {{$iterador['nombre']}} </td>
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
@foreach($res[$iterador['codigo_usuario']]['fecha'] as $periodo=>$key)

<?php 
$t1+=$res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['liquida'];
$t2+=$res[$iterador['codigo_usuario']]['salario'];
$t3+=$res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['comision'];
$t4+=$res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['lucro'];
setlocale(LC_MONETARY, 'en_US.UTF-8');
?>
<tr>
<td>{{$periodo}}</td>
<td>{{money_format('%.2n',$res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['liquida']) }}</td>
<td>{{money_format('%.2n',$res[$iterador['codigo_usuario']]['salario']) }}</td>
<td>{{money_format('%.2n',$res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['comision']) }}</td>
<td>{{money_format('%.2n',$res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['lucro']) }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tr>
<td>Saldo</td>
<td>{{money_format('%.2n',$t1)}}</td>
<td>{{money_format('%.2n',$t2)}}</td>
<td>{{money_format('%.2n',$t3)}}</td>
<td>{{money_format('%.2n',$t4)}}</td>
</tr>
</tfoot>
</table> 
<?php }; ?> 
</div>


<?php 
}#fin if del global
?>
