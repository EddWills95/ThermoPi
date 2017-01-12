
<?php
	$url1=$_SERVER['REQUEST_URI'];
	header("Refresh: 30; URL=$url1");

		$con=mysqli_connect("localhost","root","**Password In Here**","temperature");
		$sqlcurrent="SELECT current FROM temp";
		$sqltarget="SELECT target FROM temp";

			if ($result=mysqli_query($con,$sqlcurrent)))
				{
					// Fetch one and one row
				while ($row=mysqli_fetch_row($result)) {
					$current = $row[0];

				}
					// Free result set
				mysqli_free_result($result);
				}

			if ($result=mysqli_query($con,$sqltarget))
				{
					// Fetch one and one row
				while ($row=mysqli_fetch_row($result)) {
					$target = $row[0]; 
				}
					// Free result set
				mysqli_free_result($result);
				}

		mysqli_close($con);	
?>
<?php	
		$con2=mysqli_connect("localhost","root","**Password In Here**","set_temp");

		$gettimevalues="SELECT time FROM timedtemp";
		$gettempsetvalues="SELECT tempset FROM timedtemp";
        
        $query = $con2->query("SELECT `time` FROM `timedtemp`;");

        $times = Array();
        while($result = $query->fetch_assoc()){
            $times[] = $result['time'];
        }

//        print_r($times);

        $query = $con2->query("SELECT `tempset` FROM `timedtemp`;");

        $temps = Array();
        while($result = $query->fetch_assoc()){
            $temps[] = $result['tempset'];
        }

//        print_r($temps);
?>

<html>
<head>
	<title>ThermoPi</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script>
		var current = <?php echo $current; ?>;
		var target = <?php echo $target; ?>;
		var gg2; 
	</script>
	<script src="http://cdn.jsdelivr.net/mojs/latest/mo.min.js"></script>
</head>

<body>
	<script type="text/javascript">
		//Clock
		function timedMsg()
		{
			var t=setInterval("change_time();",1000);
		}
		function change_time()
		{
		   var d = new Date();
		   var curr_hour = d.getHours();
		   var curr_min = d.getMinutes();
		   var curr_sec = d.getSeconds();
		   if(curr_hour > 12)
			 curr_hour = curr_hour - 12;
		   document.getElementById('Hour').innerHTML =curr_hour+':';
			document.getElementById('Minute').innerHTML=curr_min+':';
			document.getElementById('Second').innerHTML=curr_sec;
		 }
		timedMsg();

		function startTimer(duration, display) {
			var start = Date.now(),
				diff,
				minutes,
				seconds;
			function timer() {
				// get the number of seconds that have elapsed since
				// startTimer() was called
				diff = duration - (((Date.now() - start) / 1000) | 0);

				// does the same job as parseInt truncates the float
				minutes = (diff / 60) | 0;
				seconds = (diff % 60) | 0;

				minutes = minutes < 10 ? "0" + minutes : minutes;
				seconds = seconds < 10 ? "0" + seconds : seconds;

				display.textContent = minutes + ":" + seconds;

				if (diff <= 0) {
					// add one second so that the count down starts at the full duration
					// example 05:00 not 04:59
					start = Date.now() + 1000;
				}
			};
			// we don't want to wait a full second before the timer starts
			timer();
			setInterval(timer, 1000);
}

		window.onload = function () {
			var thirtySeconds = 60 / 2,
				display = document.querySelector('#time');
			startTimer(thirtySeconds, display);
		};
	</script>

	<div class="topbar">
			<div class="topblock">
				<p>This Page will Refresh in: <span id="time"></span> seconds</p>
			</div>
			<div>
				<h1>ThermoPi/h1>
			</div>
			<div id="clockwrap" class="topblock">
				 <table id="clock">
				   <tr>
				  <td>Current time is :</td>

				  <td id="Hour" style="color:green;"></td>
				  <td id="Minute" style="color:green;"></td>
				  <td id="Second" style="color:red;"></td>
				  <tr>
				 </table>
			</div>
	</div>
			<div id="page">		
				<div id="updownbuttons" class="column">	
					<div class="buttons">
						<img src=Up%20Arrow.png id="clickup" class="arrow"/>
						<img src="Down%20Arrow.png" id="clickdown" class="arrow"/>
					</div>
					<div class="timeinput">
                        <form action="updatetimes.php" method="post">
						<table>
							<tr>
								<td>Set Time</td>
								<td>Set Target Time</td>
							</tr>
							<tr>
								<td><input type="number" id="time1" name="time[]" step="5"></td>
								<td><input type="number" id="temp1" name="temp[]"></td>	
							</tr>
							<tr>
								<td><input type="number" id="time2" name="time[]" step="5"></td>
								<td><input type="number" id="temp2" name="temp[]"></td>	
							</tr>
							<tr>
								<td><input type="number" id="time3" name="time[]" step="5"></td>
								<td><input type="number" id="temp3" name="temp[]"></td>	
							</tr>
							<tr>
								<td><input type="number" id="time4" name="time[]" step="5"></td>
								<td><input type="number" id="temp4" name="temp[]"></td>	
							</tr>
							<tr>
								<td><input type="number" id="time5" name="time[]" step="5"></td>
								<td><input type="number" id="temp5" name="temp[]"></td>	
							</tr>
							<tr>
								<td><input type="number" id="time6" name="time[]" step="5"></td>
								<td><input type="number" id="temp6" name="temp[]"></td>	
							</tr>
							<tr>
								<td><input type="number" id="time7" name="time[]" step="5"></td>
								<td><input type="number" id="temp7" name="temp[]"></td>	
							</tr>
							<tr>
								<td><input type="number" id="time8" name="time[]" step="5"></td>
								<td><input type="number" id="temp8" name="temp[]"></td>	
							</tr>
						</table>
                        
                        <input type="submit">
                        </form>
					</div>	
				</div>
                <div id="tempgauges" class="column">
					<div class="centre">
						<div id="gg1" class="gauge"></div>
						<div id="gg2" class="gauge"></div>
					</div>
				

					<script src="../raphael-2.1.4.min.js"></script>
					<script src="../justgage.js"></script>
					<script>
					var g1;
						
					document.addEventListener("DOMContentLoaded", function(event) {
						g1 = new JustGage({
							title: "Current Temperature", 
							id: "gg1",
							value: <?php echo $current; ?>,
							min: 0,
							max: 26,
							donut: true,
							gaugeWidthScale: 0.6,
							counter: true,
							hideInnerShadow: true
						});
						g2 = new JustGage({
							title: "Target Temperature", 
							id: "gg2",
							value: <?php echo $target; ?>,
							min: 0,
							max: 26,
							donut: true,
							gaugeWidthScale: 0.6,
							counter: true,
							hideInnerShadow: true
						});
						
						document.getElementById('clickup').addEventListener('click', function() {
							target++; 
							g2.refresh(target);
						});
						document.getElementById('clickdown').addEventListener('click', function() {
							target--; 
							g2.refresh(target);
						});
					});
					</script>
					
				</div>	
<!--
                <div id = "forecast" class="column">
					<div id = "map">
						<script type="text/javascript"> moWWidgetParams="moAllowUserLocation:false~moBackgroundColour:white~moColourScheme:blue~moDays:5~moDomain:www.metoffice.gov.uk~moFSSI:352409~moListStyle:vertical~moMapDisplay:side~moMapsRequired:CloudAndRain~moShowFeelsLike:true~moShowUV:true~moShowWind:true~moSpeedUnits:M~moStartupLanguage:en~moTemperatureUnits:C~moTextColour:black~moGridParams:weather,temperature,wind,feelslike,warnings~"; </script>
						<script type="text/javascript" src="http://www.metoffice.gov.uk/public/pws/components/yoursite/loader.js"> 
						</script>
					</div>
				</div>
-->
                    <script>
		
                        document.getElementById("time1").defaultValue = "<?php echo $times[0];?>"; 
                        document.getElementById("time2").defaultValue = "<?php echo $times[1];?>";
                        document.getElementById("time3").defaultValue = "<?php echo $times[2];?>"; 
                        document.getElementById("time4").defaultValue = "<?php echo $times[3];?>"; 
                        document.getElementById("time5").defaultValue = "<?php echo $times[4];?>"; 
                        document.getElementById("time6").defaultValue = "<?php echo $times[5];?>";
						document.getElementById("time7").defaultValue = "<?php echo $times[6];?>";
						document.getElementById("time8").defaultValue = "<?php echo $times[7];?>";
                        
                        document.getElementById("temp1").defaultValue = "<?php echo $temps[0];?>"; 
                        document.getElementById("temp2").defaultValue = "<?php echo $temps[1];?>";
                        document.getElementById("temp3").defaultValue = "<?php echo $temps[2];?>"; 
                        document.getElementById("temp4").defaultValue = "<?php echo $temps[3];?>"; 
                        document.getElementById("temp5").defaultValue = "<?php echo $temps[4];?>"; 
                        document.getElementById("temp6").defaultValue = "<?php echo $temps[5];?>";
						document.getElementById("temp7").defaultValue = "<?php echo $temps[6];?>"; 
						document.getElementById("temp8").defaultValue = "<?php echo $temps[7];?>"; 
                
                    </script>
					<script>
						$("#clickup").click(function(){
							$.ajax({
								type: "POST",
								url: "targetup.php",
								succes: function(){
								}
								});
							gg2.refresh(); 
						});
						$("#clickdown").click(function(){
							$.ajax({
								type: "POST",
								url: "targetdown.php",
								succes: function () {
								}
							});
							$('#tempgauges').load('index.php' + ' #tempgauges', MakeGauges); 
						});
					</script>
					
			</div>
        </body>
</html>




