

<script src="{{ url('js/jsfunctions.js')}}"></script>


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

                <tbody >
                  <tr bgcolor="#616161">
                    <td width="10%" nowrap="nowrap"><div align="right">Período:</div></td>
                    <td> Fecha Inicio {!! Form::date('fecha_inicio', \Carbon\Carbon::createFromDate(2007,1,1), ['id'=>'fecha_inicio']) !!} Fecha Final {!! Form::date('fecha_final', \Carbon\Carbon::createFromDate(2007,5,31), ['id'=>'fecha_final']) !!}

                        {!! Form::hidden('url', 'http://'.$_SERVER['HTTP_HOST'] , ['id'=>'url']) !!}

                    </td>
                    <td width="20%" rowspan="2"><div align="center">
                      <font color="black">
                      
                        <input class="multiselect_submit_button" style="BACKGROUND-IMAGE: url(img/icone_relatorio.png);" type="submit" value="Relatório" id='relatorio' onclick="relatorio()" href="#" >

                        <input class="multiselect_submit_button" style="BACKGROUND-IMAGE: url(img/icone_grafico.png);" type="submit" value="Gráfico"  id='grafico' onclick="grafico()" href="#">

                        <input class="multiselect_submit_button" style="BACKGROUND-IMAGE: url(img/icone_pizza.png);" type="submit" value="Pizza" id='pizza' onclick="pizza()" href="#">
          
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

                            {{-- {!! Form::select('list1',$consultores, null, ['multiple'=>'true', 'id'=>'list1','size'=>8,'style'=>'min-width:280px']) !!} --}}

                          </td>
                          <td align="center" valign="middle"><input name="button" type="button" onclick="move(list1,list2)" value=">>">
                              <br>
                              <input name="button" type="button" onclick="move(list2,list1)" value="<<">
                          </td>
                          <td>

                           

                            {!! Form::select('list2', array(), null, ['multiple'=>'true', 'id'=>'list2','required','size'=>8,'style'=>'min-width:280px']) !!}
                              
                          </td>
                            
                        </tr>
                    </tbody></table></td>
                  </tr>
                </tbody>
              </table>
</div>
{{-- fin div de multiselect --}}

<br>

<div id="deploy_area"  ></div>
