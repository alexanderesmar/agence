<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<SCRIPT language="JavaScript">
//<!--
//<!-- comeco do selection box
function move(fbox, tbox) {
var arrFbox = new Array();
var arrTbox = new Array();
var arrLookup = new Array();
var i;
for (i = 0; i < tbox.options.length; i++) {
arrLookup[tbox.options[i].text] = tbox.options[i].value;
arrTbox[i] = tbox.options[i].text;
}
var fLength = 0;
var tLength = arrTbox.length;
for(i = 0; i < fbox.options.length; i++) {
arrLookup[fbox.options[i].text] = fbox.options[i].value;
if (fbox.options[i].selected && fbox.options[i].value != "") {
arrTbox[tLength] = fbox.options[i].text;
tLength++;
}
else {
arrFbox[fLength] = fbox.options[i].text;
fLength++;
   }
}
arrFbox.sort();
arrTbox.sort();
fbox.length = 0;
tbox.length = 0;
var c;
for(c = 0; c < arrFbox.length; c++) {
var no = new Option();
no.value = arrLookup[arrFbox[c]];
no.text = arrFbox[c];
fbox[c] = no;
}
for(c = 0; c < arrTbox.length; c++) {
var no = new Option();
no.value = arrLookup[arrTbox[c]];
no.text = arrTbox[c];
tbox[c] = no;
   }
}
//  fim de selection box -->

function calc_selected($submit_type){

    /*console.log($submit_type);*/

    var selected =  Object.values(document.getElementById('list2').options);

    var send_selected=[];

    selected.forEach(function(index) {

      send_selected.push(index.value);

    });

    document.getElementById('submit_type').value=$submit_type;

    document.getElementById('selected').value=send_selected;

    var fecha_i = document.getElementById('fecha_inicio').value;
    var fecha_f = document.getElementById('fecha_final').value;
    var url = document.getElementById('url').value;

    var url_send = url +'/performance/'+$submit_type+'/'+send_selected+'/'+fecha_i+'/'+fecha_f;
    
    document.getElementById('url_send').value=url_send;

     /*$(document).ready(function(){

      $.ajax({
        url: "http://localhost:8000/performance/pizza/anapaula.chiodaro,renato.pereira/2003-01-01/2007-12-31",
        method: "GET",
        success: function(data){
          console.log(data);
          alert(document.getElementById('url_send').value);
        },
        error: function (data){
          console.log(data);
        }
      });

     });*/
}

function checkOptions(){

        return false;

     }

//-->
</script>


<?php 

#dd($consultores); 
if (isset($GLOBALS["consultores"])) {
  $consultores=$GLOBALS["consultores"];
}

?>

<style type="text/css">
    .multiselect_submit_button{

        BORDER-RIGHT: 1px outset;
        BORDER-TOP: 1px outset;
        FONT-SIZE: 8pt;
        BACKGROUND-POSITION-Y: center;
        LEFT: 120px;
        BACKGROUND-IMAGE: url(img/icone_relatorio.png);
        BORDER-LEFT: 1px outset;
        WIDTH: 110px;
        BORDER-BOTTOM: 1px outset;
        BACKGROUND-REPEAT: no-repeat;
        FONT-FAMILY: Tahoma, Verdana, Arial;
        HEIGHT: 22px; BACKGROUND-COLOR: #CCCCCC;

    }
</style>

{{-- div de multiselect --}}
<div>
    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#cccccc" id="pesquisaAvancada" class="mdl-color--grey-700 mdl-card dark-table mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-color-text--white">

            {!! Form::open(  ['action' => ['PerformanceController@calc_view'],'method' => 'post']) !!}

                <tbody >
                  <tr bgcolor="#616161">
                    <td width="10%" nowrap="nowrap"><div align="right">Período:</div></td>
                    <td> Fecha Inicio {!! Form::date('fecha_inicio', \Carbon\Carbon::createFromDate(2003,1,1), ['id'=>'fecha_inicio']) !!} Fecha Final {!! Form::date('fecha_final', \Carbon\Carbon::createFromDate(2007,12,31), ['id'=>'fecha_final']) !!}

                        {!! Form::hidden('selected', '', ['id'=>'selected','required']) !!}
                        {!! Form::hidden('submit_type', '', ['id'=>'submit_type']) !!}
                        {!! Form::hidden('url', 'http://'.$_SERVER['HTTP_HOST'] , ['id'=>'url']) !!}
                        {!! Form::hidden('url_send', '' , ['id'=>'url_send']) !!}

                    </td>
                    <td width="20%" rowspan="2"><div align="center">
                      <font color="black">
                      
                        <input class="multiselect_submit_button" style="BACKGROUND-IMAGE: url(img/icone_relatorio.png);" type="submit" value="Relatório" id='relatorio' name="btSalvar22" onclick="calc_selected(this.id)" onsubmit="checkOptions()">

                        <input class="multiselect_submit_button" style="BACKGROUND-IMAGE: url(img/icone_grafico.png);" type="submit" value="Gráfico" name="btSalvar222"  id='grafico' onclick="calc_selected(this.id)" onsubmit="checkOptions()">

                        <input class="multiselect_submit_button" style="BACKGROUND-IMAGE: url(img/icone_pizza.png);" type="submit" value="Pizza" name="btSalvar222" id='pizza' onclick="calc_selected(this.id)" onsubmit="checkOptions()">
            {!! Form::close() !!}
                    </font></div></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap"><div align="right">Consultores</div></td>
                    <td><table align="center">
                        <tbody><tr>
                          <td>
                            <select multiple="" size="8" name="list1" id="list1" style="min-width:280px">

                                @foreach($consultores as $iteracion)

                                <option value="{{$iteracion->co_usuario}}">{{$iteracion->no_usuario}}</option>

                                @endforeach
                              
                            </select>
                          </td>
                          <td align="center" valign="middle"><input name="button" type="button" onclick="move(list1,list2)" value=">>">
                              <br>
                              <input name="button" type="button" onclick="move(list2,list1)" value="<<">
                          </td>
                          <td><select multiple="" size="8" name="list2" id="list2" style="min-width:280px">
                            </select>
                              <input type="hidden" name="lista_analista" id="lista_analista" value="">
                          </td>
                        </tr>
                    </tbody></table></td>
                  </tr>
                </tbody>
              </table>
</div>
{{-- fin div de multiselect --}}

<?php 
if (isset($GLOBALS["submit_type"])) {

  if (View::exists('performance.partials.'.$GLOBALS["submit_type"] )) { 

    if (isset($GLOBALS["usuario_no_data"])) {
      $usuario_no_data=$GLOBALS["usuario_no_data"];


      /*if( $usuario_no_data['status']==true){
       echo "no se encontraron datos de ". $usuario_no_data['users'].' en el periodo especificado';
       }*/

    }



    ?>

  @include('performance.partials.'.$GLOBALS["submit_type"])

  <?php 

  } 

} ?>