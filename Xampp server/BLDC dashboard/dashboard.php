<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sensor Data Upload</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script type="text/javascript">
  


var jsonTable_one;
var jsonTable_two;
var healthy = 0;
var inner = 0;
var outer = 0;
var ohealthy = 0;
var oinner = 0;
var oouter = 0;
var oldoff = 0;
var export_one;
var export_two;
var ovdata = 0;
$('.ei1').hide();
$('.ei2').hide();

var timer = setInterval(loaddata,2000);

function loaddata(){	

   var vdata = $("#phase").val();
   
   if(vdata!=0){
	
	if(vdata!=ovdata){
		oldoff = 0;
	}
	
	$.post( "fecthapi.php", { vdata: vdata,oldoff:oldoff })
	.done(function( data ) { 
	ovdata = vdata;
	var obj = jQuery.parseJSON( data );
	
	ohealthy = healthy;
	oouter = outer;
	oinner = inner;
	
	jsonTable_one = obj.jsonTable_one;
	jsonTable_two = obj.jsonTable_two;
	healthy = obj.healthy;
	inner = obj.inner;
	outer = obj.outer;
	oldoff = obj.oldoff;
  
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart_one);
	function drawChart_one()
	{
		var data = new google.visualization.DataTable(jsonTable_one);
	
		var options = {
		title:'X (t)',
		legend:{position:'bottom'},
		chartArea:{width:'90%', height:'65%'}
		};
		
	
		var chart_one = new google.visualization.LineChart(document.getElementById('line_chart_one'));
		
		google.visualization.events.addListener(chart_one, 'ready', function ()
		{
			export_one = chart_one.getImageURI();
			$(".ei1").css("display","block");
			
		});
		
		chart_one.draw(data, options);
   
	}
   
	google.charts.setOnLoadCallback(drawChart_two);
	function drawChart_two()
	{
		var data = new google.visualization.DataTable(jsonTable_two);
	
		var options = {
		title:'P1 (f)',
		legend:{position:'bottom'},
		chartArea:{width:'90%', height:'65%'}
		};
	
		var chart_two = new google.visualization.LineChart(document.getElementById('line_chart_two'));
		
		google.visualization.events.addListener(chart_two, 'ready', function ()
		{
			export_two = chart_two.getImageURI();
			$(".ei2").css("display","block");
			
		});
		
		chart_two.draw(data, options);
   
	}
   
	google.charts.load('current', {'packages':['gauge']});
    google.charts.setOnLoadCallback(drawChart_three);

	function drawChart_three() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['', oinner]
        ]);

        var options = {
          width: 180, height: 180,
          yellowFrom:0, yellowTo: 100,
          minorTicks: 0,
          majorTicks: ['0.00%', '100.00%']
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div_one'));
        
        var formatnumbers = new google.visualization.NumberFormat({
            suffix: '%',
            fractionDigits: 2
          });
          formatnumbers.format(data, 1);


        chart.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, inner);
          chart.draw(data, options);
        }, 1000);
		
    }
	  
	google.charts.setOnLoadCallback(drawChart_four);

    function drawChart_four() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['', oouter]
        ]);

        var options = {
          width: 180, height: 180,
          redFrom: 0, redTo: 100,
          minorTicks: 0,
          majorTicks: ['0.00%', '100.00%']
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div_two'));

        var formatnumbers = new google.visualization.NumberFormat({
            suffix: '%',
            fractionDigits: 2
          });
          formatnumbers.format(data, 1);

        chart.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, outer);
          chart.draw(data, options);
        }, 1000);
        
	}
	
	google.charts.setOnLoadCallback(drawChart_five);

    function drawChart_five() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['', ohealthy]
        ]);

        var options = {
          width: 180, height: 180,
          greenFrom: 0, greenTo: 100,
          minorTicks: 0,
          majorTicks: ['0.00%', '100.00%']
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div_three'));
        var formatnumbers = new google.visualization.NumberFormat({
            suffix: '%',
            fractionDigits: 2
          });
          formatnumbers.format(data, 1);
          
        chart.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, healthy);
          chart.draw(data, options);
        }, 1000);
        
	}
	
	play_sound();
	
    });
   
   }
   
   
}

	

	function ExportImage_one() {
            var string = export_one;
			var a = document.createElement("a"); //Create <a>
			a.href = string; //Image Base64 Goes here
			a.download = "ChartImageOne.png"; //File name Here
			a.click(); //Downloaded file
    }
	
	function ExportImage_two() {
            var string = export_two;
            var a = document.createElement("a"); //Create <a>
			a.href = string; //Image Base64 Goes here
			a.download = "ChartImageTwo.png"; //File name Here
			a.click(); //Downloaded file
    }
	
	function play_sound(){
        let audio_ctx = new AudioContext();

        let volume = 100;
        let frequency = 900;
        let oscillation_type = "sine";
        let duration = 0.05;

        let oscillator = audio_ctx.createOscillator();
        let gain = audio_ctx.createGain();

        oscillator.connect(gain);
        oscillator.frequency.value = frequency;
        oscillator.type = oscillation_type;

        gain.connect(audio_ctx.destination);
        
        gain.gain.value = volume;
                
        oscillator.start(audio_ctx.currentTime);
        oscillator.stop(audio_ctx.currentTime+duration);
    }
  </script>
  
<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600&display=swap');
*{
	margin:0;
	padding:0;
	text-align:center;
}
body{
	font-family: 'Poppins', sans-serif !important;
}
.top-fix{
	position:fixed;
	right:0;
	left:0;
	width:100%;
	bottom:0px;
	height:11vh;
}
.ei1,.ei2{
	display:none;
	width:200px;
	height:50px!important;
	padding:0px;
	line-height:50px;
	margin-left:auto;
	margin-right:auto;
	color: #3283f6;
	font-weight:500 ;
    background-color: rgba(50,131,246,.18);
    border-color: rgba(50,131,246,.12);
}
.card{
	border-radius:0px;
	border:0px;
	padding:0px;
	margin:0px;
}

#phase{
	margin-bottom: 20px;
}
.main-btn {
	height:60px !important;
	line-height: 60px !important;
	padding: 0px !important;
}
.text-dark {
	font-size: 22px !important;
	color: #000 !important;
}

</style>  
  
</head>
<body class="bg-light">

<div class="container-fluid mt-2 mb-2"> 


<div class="col-md-12 mt-4">

	<div class="row">
	
	<div class="col-md-6 mt-4">
	
	<h5 class="text-dark text-center pb-2">Original Signal in Time Domain</h5>
	<div id="line_chart_one" style="width: 100%; height: 400px;" class="mt-4 mb-4 pb-4 card card-body shadow-sm"></div>
	<a onclick="ExportImage_one()" class="ei1 btn btn-primary mt-4 mb-4  shadow-sm">Export as Image</a>
	</div>
	
	<div class="col-md-6 mt-4">
	
	<h5 class="text-dark text-center pb-2">Single-Sided Amplitude Spectrum of X(t)</h5>
	<div id="line_chart_two" style="width: 100%; height: 400px;" class="mt-4 mb-4 pb-4 card card-body shadow-sm"></div>
	<a onclick="ExportImage_two()" class="ei2 btn btn-primary mt-4 mb-4 shadow-sm">Export as Image</a>
	</div>
	
	</div>

	
	<div class="row container-fluid">
	<div class="col-md-9 card card-body shadow-sm">
	<div class="row">
	
	<div class="col-md-4">
	
	<br>
	<div id="chart_div_one" style="width: 180px; height: 180px;margin-left:auto;margin-right:auto;"></div>
	<h6 class="text-dark text-center mt-2 mb-4">Inner Faulty Data</h6>
	</div>
	
	<div class="col-md-4">
	
	<br>
	<div id="chart_div_two" style="width: 180px; height: 180px;margin-left:auto;margin-right:auto;"></div>
	<h6 class="text-dark text-center mt-2 mb-4">Outer Faulty Data</h6>
	
	</div>
	
	<div class="col-md-4">
	
	<br>
	<div id="chart_div_three" style="width: 180px; height: 180px;margin-left:auto;margin-right:auto;"></div>
	<h6 class="text-dark text-center mt-2 mb-4">Healthy Data</h6>
	</div>
	</div>
	</div>
	<div class="col-md-3">
	<div class="pb-4 card card-body shadow-sm pl-4 pr-4 pt-4 pb-4">
	<br>
	<br>
	<label for="pwd">Select Phase:</label>
	<select id="phase" class="custom-select shadow-sm">
		<option selectet value="0">Off</option>
		<option value="1">Phase 1</option>
		<option value="2">Phase 2</option>
		<option value="3">Phase 3</option>
	</select>
	<a href="index.php" class="btn btn-primary mt-2 btn-block shadow-sm main-btn">Go to Main</a>
	<br>
	<br>
	<br>
	</div>
	
	</div>
	
	</div>
	
	</div>
	
</div>


</div>

</body>
</html>
