
<?php
if (isset($res) && $res!="empty"  ) {

?>

<div id="show_relatorio" align="center">

<?php foreach ($res as $iterador) { 

$t1=0;
$t2=0;
$t3=0;
$t4=0;

  ?>
<br>
<table  style="overflow-x:auto" class="greyGridTable">
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
<td>{{'R$ '.number_format($res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['liquida'],2) }}</td>
<td>{{'R$ '.number_format($res[$iterador['codigo_usuario']]['salario']) }}</td>
<td>{{'R$ '.number_format($res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['comision'],2) }}</td>
<td style="color: {{$color}};">{{'R$ '.number_format($res[$iterador['codigo_usuario']]['fecha'][ $periodo ]['lucro'],2) }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tr>
<td>Saldo</td>
<td>{{'R$ '.number_format($t1,2)}}</td>
<td>{{'R$ '.number_format($t2,2)}}</td>
<td>{{'R$ '.number_format($t3,2)}}</td>
<?php if ($t4>0) { $color='green'; } else{ $color='red'; } ?>
<td style="color: {{$color}};" >{{'R$ '.number_format($t4,2)}}</td>
</tr>
</tfoot>
</table> 
<?php }; ?> 
</div>


<?php 
}else if($res=="empty"){ ?>
empty
<?php
}#fin if del global
?>
