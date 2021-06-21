<?php

include ('yandex.php');

$url = 'http://api.openweathermap.org/data/2.5/weather';

$options = array(
	'id' => 472231,
	'appid' => 'eed662e679edbffbea195ad9f83281c4',
	'units' => 'metric',
	'lang' => 'en'
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($options));

$response = curl_exec($ch);
$data = json_decode($response, true);

curl_close($ch);

$conn = mysqli_connect('localhost', 'root', '', 'weather');
 
if (!$conn) {
  die("Ошибка соединения: " . mysqli_connect_error());
}
echo "Соединение установлено";
echo "<br>";

$sql = "INSERT INTO openwmap (Temp) VALUES ('{$data['main']['temp']}')";
if (mysqli_query($conn, $sql)) {
      echo "Новая запись добавлена";
      echo '<br>';
} else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

	$data1 = '';
	$data2 = '';
	
	// График 1
	$sql1 = "SELECT * FROM `openwmap` ";
    $result1 = mysqli_query($conn, $sql1);

	while ($row = mysqli_fetch_array($result1)) {

		$data1 = $data1 . '"'. $row['Temp'].'",';
	}

	$data1 = trim($data1,",");

	// График 2
	$sql2 = "SELECT * FROM `yandex` ";
    $result2 = mysqli_query($conn, $sql2);

	while ($row = mysqli_fetch_array($result2)) {

		$data2 = $data2 . '"'. $row['Temp'].'",';
	}

	$data2 = trim($data2,",");
	
?>

<!DOCTYPE html>
<html>
	<head>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
		<title>Accelerometer data</title>

		<style type="text/css">			
			body{
				font-family: Arial;
			    margin: 80px 100px 10px 100px;
			    padding: 0;
			    color: white;
			    text-align: center;
			    background: #555652;
			}

			.container {
				color: #E8E9EB;
				background: #222;
				border: #555652 1px solid;
				padding: 10px;
			}
		</style>

	</head>

	<body>	   
	    <div class="container">	
	    <h1>USE CHART.JS WITH MYSQL DATASETS</h1>       
			<canvas id="chart" style="width: 100%; height: 65vh; background: #222; border: 1px solid #555652; margin-top: 10px;"></canvas>

			<script>
				var ctx = document.getElementById("chart").getContext('2d');
    			var myChart = new Chart(ctx, {
        		type: 'line',
		        data: {
		            labels: [1,2,3,4,5,6,7,8,9],
		            datasets: 
		            [{
		                label: 'OpenWeather: ',
		                data: [<?php echo $data1; ?>],
		                backgroundColor: 'transparent',
		                borderColor:'rgba(255,99,132)',
		                borderWidth: 3
		            },
					
		            {
		            	label: 'Yandex: ',
		                data: [<?php echo $data2; ?>],
		                backgroundColor: 'transparent',
		                borderColor:'rgba(0,255,255)',
		                borderWidth: 3	
		            }
					
					]
		        },
		     
		        options: {
		            scales: {scales:{yAxes: [{beginAtZero: false}], xAxes: [{autoskip: true, maxTicketsLimit: 20}]}},
		            tooltips:{mode: 'index'},
		            legend:{display: true, position: 'top', labels: {fontColor: 'rgb(255,255,255)', fontSize: 16}}
		        }
		    });
			</script>
	    </div>
	    
	</body>
</html>