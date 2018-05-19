
<?php
if (isset($GLOBALS["res"])) {
  $res=$GLOBALS["res"];

?>

<div id="show_relatorio" style="padding-left: 30%;" >

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

if ($res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['lucro']>0) {
 $color='green';
}
else{
  $color='red';
}
?>
<tr>
<td>{{$periodo}}</td>
<td>{{$res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['liquida'] }}</td>
<td>{{$res[$iterador['codigo_usuario']]['salario'] }}</td>
<td>{{$res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['comision'] }}</td>
<td style="color: {{$color}};" >{{$res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['lucro'] }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tr>
<td>Saldo</td>
<td>{{$t1}}</td>
<td>{{$t2}}</td>
<td>{{$t3}}</td>
<?php if ($t4>0) { $color='green'; } else{ $color='red'; } ?>
<td style="color: {{$color}};" >{{$t4}}</td>
</tr>
</tfoot>
</table> 
<?php }; ?> 
</div>


<?php 
}#fin if del global
?>
