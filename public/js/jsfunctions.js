
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

/* document.getElementById('list2').selectedIndex =0;*/

}
//  fim de selection box -->

//-->

function get_basics() {


  var fecha_inicio = $('#fecha_inicio').val();
    var fecha_final = $('#fecha_final').val();
    var selected =  Object.values(document.getElementById('list2').options);
    var send_selected=[];

    if(selected.length == 0){

        alert("NO ha selecionado ningun consultor, debe seleccionar al menos un consultor");
      
        return false;
    }else{


    selected.forEach(function(index) {

      send_selected.push(index.value);

      $("#list2"+" option[value="+this.value+"]").prop("selected",true);

    });

    data = {
        fecha_inicio:fecha_inicio,
        fecha_final:fecha_final,
        send_selected:send_selected,

    }  

  return data;
  }
}


function procesar(datos,tipo) {

  switch(tipo){

    case 'pizza':


      var userName = [];
         /* var userGanancia = [];*/
          var userPorcentaje = [];


          var totalFacturas=0.0;

          for (var j in datos){
            totalFacturas+=datos[j].ganancia;

          }

          for (var i in datos){
            userName.push( datos[i].no_usuario+': '+( ( (datos[i].ganancia*100)/totalFacturas).toFixed(2) ).toString() +' %' );
            /*userGanancia.push( datos[i].ganancia );*/
            userPorcentaje.push( ( (datos[i].ganancia*100)/totalFacturas).toFixed(2) ) ;

            /*userPorcentaje.push( ( ( (datos[i].ganancia*100)/totalFacturas).toFixed(2) ).toString() +' %' );*/
          }




          var ctx = document.getElementById("myChart").getContext('2d');

            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: userName,
                    datasets: [{
                        label: userName,
                        data: userPorcentaje,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(205, 26, 186, 0.8)',
                            'rgba(55, 12, 12, 0.8)',
                            'rgba(133, 144, 10, 0.8)',
                            'rgba(23, 123, 200, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(205, 26, 186, 0.8)',
                            'rgba(55, 12, 12, 0.8)',
                            'rgba(133, 144, 10, 0.8)',
                            'rgba(23, 123, 200, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                  responsive: true,
                  legend: {

                          display: true,
                          position: 'right',
                          fullWidth: true, 
                          reverse: false,
                      },
                    title: {
                        display:true,
                        text:'Participação na Receita'
                    }
                }
            });

     break;

    case 'grafico':

      var personas=[]; 

      personas.push('Month');


      for(var i in datos){

        for(var j in datos[i].usuario){

          personas.push(datos[i].usuario[j]);

        }

      }

      var tabla_datos=[
             personas
             
          ];

      
      for(var i in datos){

        for(var j in datos[i].fecha_data){

          tabla_datos.push(datos[i].fecha_data[j]);

        }

      }

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable(tabla_datos);

        var options = {
          title : 'Performance Comercial',
          vAxis: {title: 'Receita'},
          hAxis: {title: 'Meses'},
          seriesType: 'bars',
          series: {0: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }

      break;

  }

}


function deploy(data, tipo) {

  var area_grafico='';
  var deploy_area = $('#deploy_area');
  var error=false;

  if ( typeof(data) == 'string' ) {

    if (data.search('empty')>-1) {

      error=true;
      deploy_area.empty();
    }

  }

  if (error) {

    alert('No hay datos del consultor o consultores seleccionados en este periodo');

  }else{

  
    switch(tipo) {

      case 'relatorio':

        area_grafico = data;

        deploy_area.empty();
        deploy_area.append(area_grafico);

       break;

      case 'pizza':

        area_grafico = '<div id="show_grafico" style="padding-left: 10%; width: 900px;" ><canvas id="myChart" width="900px" height="400px"></canvas></div>';

        deploy_area.empty();
        deploy_area.append(area_grafico);
        procesar(data,tipo);

       break;


      case 'grafico':

        area_grafico = '<div id="chart_div" style="width: 900px; height: 500px;"></div>';

        deploy_area.empty();
        deploy_area.append(area_grafico);
        procesar(data,tipo);

       break;
      
     }/*fin switch*/


    }/*fin verificacion de datos*/

}

function grafico(){

  var useUrl = $('#url').val();

  if ( useUrl.search("localhost")<0 ) {

    useUrl = useUrl.replace("http:", "https:");

   }

  var data=get_basics();

  if ( data!=false ){

      $.get(useUrl+'/grafico', data).done(function(datos_json){

          deploy(datos_json,'grafico');

      }); 

  }
}


function relatorio(){

  var useUrl = $('#url').val();

  if ( useUrl.search("localhost")<0 ) {

    useUrl = useUrl.replace("http:", "https:");

   }

  var data=get_basics();

  if ( data!=false ){

      $.get(useUrl+'/relatorio', data).done(function(datos_json){

          deploy(datos_json,'relatorio');

      }); 

  }
}



function pizza(){

  var useUrl = $('#url').val();

  if ( useUrl.search("localhost")<0 ) {

    useUrl = useUrl.replace("http:", "https:");

   }

  var data=get_basics();

  if ( data!=false ){

      $.get(useUrl+'/pizza', data).done(function(datos_json){

          deploy(datos_json,'pizza');

      }); 

  }
  
}
