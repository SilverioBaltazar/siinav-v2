@extends('sicinar.principal')

@section('title','Estadistica por rubro social')

@section('links')
  <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
{{$nombre}}
@endsection

@section('usuario')
{{$usuario}}
@endsection

@section('estructura')
{{$estructura}}
@endsection

@section('content')
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Numeralia Básica
        <small> Gráfica</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Numeralia</a></li>
        <li><a href="#">Estadisticas</a></li>
        <li class="active">IAPS por Rubro social</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title"><b>Estadistica de IAPS por Rubro Social</b></h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
              <table id="tabla1" class="table table-striped table-bordered table-sm">
                <thead style="color: brown;" class="justify">
                  <tr>
                    <th rowspan="2" style="text-align:left;"  >ID.         </th>
                    <th rowspan="2" style="text-align:left;"  >RUBRO SOCIAL</th>
                    <th rowspan="2" style="text-align:center;">TOTAL       </th>
                  </tr>
                  <tr>
                  </tr>
                </thead>

                <tbody>
                  @foreach($regiap as $iap)
                    <tr>
                      @if($iap->rubro_id == 0)
                         <td style="color:darkgreen;">{{$iap->rubro_id}}</td>
                         <td style="color:darkgreen;">{{$iap->rubro}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 1)
                         <td style="color:red;">{{$iap->rubro_id}}</td>
                         <td style="color:red;">{{$iap->rubro}}   </td>
                         <td style="color:red; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 2)
                         <td style="color:orange;">{{$iap->rubro_id}}</td>
                         <td style="color:orange;">{{$iap->rubro}}   </td>
                         <td style="color:orange; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 3)
                         <td style="color:blue;">{{$iap->rubro_id}}</td>
                         <td style="color:blue;">{{$iap->rubro}}   </td>
                         <td style="color:blue; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 4)
                         <td style="color:grey;">{{$iap->rubro_id}}</td>
                         <td style="color:grey;">{{$iap->rubro}}   </td>
                         <td style="color:grey; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 5)
                         <td style="color:purple;">{{$iap->rubro_id}}</td>
                         <td style="color:purple;">{{$iap->rubro}}   </td>
                         <td style="color:purple; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 6)
                          <td style="color:dodgerblue;">{{$iap->rubro_id}}</td>
                          <td style="color:dodgerblue;">{{$iap->rubro}}   </td>
                          <td style="color:dodgerblue; text-align:center;">{{$iap->total}}   </td>
                      @endif
                    </tr>
                  @endforeach
                  @foreach($regtotxrubro as $totales)
                    <tr>
                        <td></td>
                        <td style="color:green;"><b>TOTAL</b></td>                         
                        <td style="color:green; text-align:center;"><b>{{$totales->totalxrubro}} </b></td>                      
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Grafica de pie en 3D-->
        <div class="col-md-6">
          <div class="box">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title" style="text-align:center;">Gráfica de Pay 3D </h3> 
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <div class="box-body">
                  <camvas id="piechart_3d" style="width: 900px; height: 500px;"></camvas>
                </div>
              </div> 
            </div>
          </div>
        </div>


      </div>


      <!-- Grafica de barras 2D-->
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box box-success">
              <!-- <div class="box-header with-border"> -->
                <!--<h3 class="box-title" style="text-align:center;">Gráfica por Rubro social 2D </h3> -->
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <div class="box-body">
                  <camvas id="top_x_div" style="width: 900px; height: 500px;"></camvas>
                </div>
              <!-- </div> -->
            </div>
          </div>
        </div>

        <!-- Grafica de dona 
        Making a donut chart
        https://developers.google.com/chart/interactive/docs/gallery/piechart#donut
        -->
        <div class="col-md-6">
          <div class="box">
            <div class="box box-danger">
              <!-- <div class="box-header with-border"> -->
                <!-- <h3 class="box-title" style="text-align:center;">Gráfica por Rubro social 3D </h3> -->
                <!-- BOTON para cerrar ventana x -->
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <!-- Pinta la grafica de barras 2-->
                <div class="box-body">
                  <camvas id="donutchart" style="width: 900px; height: 500px;"></camvas>
                </div>
              <!-- </div> -->
            </div>
          </div>
        </div>        

      </div>      

    </section>
  </div>
@endsection

@section('request')
  <script src="{{ asset('bower_components/chart.js/Chart.js') }}"></script>
  
  <!-- Grafica de pay, barras y otras
    https://google-developers.appspot.com/chart/interactive/docs/gallery/piechart
    https://www.youtube.com/watch?v=Y83fxTpNSsY
  -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection

@section('javascrpt')

  
  <!-- Grafica de pie (pay) 2D Google/chart -->
  <script type="text/javascript">
      // https://www.youtube.com/watch?v=Y83fxTpNSsY ejemplo de grafica de pay google
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['id', 'Rubro social'],
          @foreach($regiap as $iap)
             ['{{$iap->rubro}}', {{$iap->total}} ],
          @endforeach
          //['Work',     11],
          //['Eat',      2],
          //['Commute',  2],
          //['Watch TV', 2],
          //['Sleep',    7]
        ]);

        var options = {
          title: 'IAPS por Rubro Social',
          //chart: { title: 'Gráfica de Pay',
          //         subtitle: 'IAPS por Rubro Social' },          
          is3D: true,
          width: 700,                   // Ancho de la pantalla horizontal
          height: 500,                  // Alto de la pantall '75%',          
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
  </script>

  <!-- Grafica de barras 2D Google/chart -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Rubro Social', 'Total'],
          @foreach($regiap as $iap)
             ['{{$iap->rubro}}', {{$iap->total}} ],
          @endforeach          
          //["King's pawn (e4)", 44],
          //["Queen's pawn (d4)", 31],
          //["Knight to King 3 (Nf3)", 12],
          //["Queen's bishop pawn (c4)", 10],
          //['Other', 3]
        ]);

        var options = {
          title: 'Por Rubro social',
          width: 700,                   // Ancho de la pantalla horizontal
          height: 500,                  // Alto de la pantall '75%',          
          legend: { position: 'none' },
          chart: { title: 'Gráfica de Barras',
                   subtitle: 'IAPS por Rubro Social' },
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              //0: { side: 'top', label: 'Total de IAPS'} // Top x-axis.
              1: { side: 'top', label: 'Total de IAPS'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };
  </script>  

  <!-- Grafica de dona 
  Making a donut chart
  https://developers.google.com/chart/interactive/docs/gallery/piechart#donut
  -->
  <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Rubro Social', 'Total'],
          @foreach($regiap as $iap)
             ['{{$iap->rubro}}', {{$iap->total}} ],
          @endforeach            
          //['Work',     11],
          //['Eat',      2],
          //['Commute',  2],
          //['Watch TV', 2],
          //['Sleep',    7]
        ]);

        var options = {
          title: 'Gráfica por Rubro Social',
          pieHole: 0.4,
          width: 700,                   // Ancho de la pantalla horizontal
          height: 500,                  // Alto de la pantall '75%',
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
  </script>  
@endsection