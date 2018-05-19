
<?php
if (isset($GLOBALS["res"])) {

?>

{!! Form::hidden('url_send', '' , ['id'=>'url_send']) !!}

<script type="text/javascript">

    var useUrl = document.getElementById('url_send').value;

/*    alert( useUrl );

    console.log( useUrl);*/

         $(document).ready(function(){

      $.ajax({
        url: useUrl,
        method: "GET",
        success: function(data){
/*          console.log('succes');
          console.log(data);*/
          var userName = [];
          var userGanancia = [];


          var aux = JSON.parse(data);
          /*console.log(typeof(aux));*/

          for (var i in aux){
            userName.push(aux[i].no_usuario);
            userGanancia.push(aux[i].ganancia);
          }

          var ctx = document.getElementById("myChart").getContext('2d');

            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: userName,
                    datasets: [{
                        label: '# of Votes',
                        data: userGanancia,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    title: {
                        display:true,
                        text:'Participação na Receita'
                    }
                }
            });

        },
        error: function (data){
          console.log('error');
          console.log(data);
          alert('no se han podido obtener todos los datos');
        }
      });

     });
</script>

<div id="show_grafico" style="padding-left: 30%;" >
	<canvas id="myChart" width="400" height="400"></canvas>
</div>


<?php 
}#fin if del global
?>
