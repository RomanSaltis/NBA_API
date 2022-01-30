<?php
if (!isset($_GET['date'])||!isset($_GET['home_team'])||!isset($_GET['visitor_team'])){
header('Location: index.php');
}

$curl = curl_init();
$response = "./players.json";

if(file_get_contents($response) == false){
$response=[];
foreach(range(1, 5) as $i){
curl_setopt_array($curl, [
	CURLOPT_URL => "https://free-nba.p.rapidapi.com/players?page=$i&per_page=100",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 3000,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"x-rapidapi-host: free-nba.p.rapidapi.com",
		"x-rapidapi-key: 77f3ed6c8amsh13ca77352a076efp19e993jsn0aabab6eef73"
	],
]);

$response[] = json_decode(curl_exec($curl), true);
$err = curl_error($curl);
curl_close($curl);
}

if(!file_exists("./players.json")){
        file_put_contents("./players.json", "[]");
}

function setData($response){
    file_put_contents('./players.json', json_encode($response));
}

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	setData($response);
}
}
$decoded = json_decode(file_get_contents('./players.json'), true);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Saltis REST API players</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
			<div class="p-3 mb-2 bg-light text-dark">
			<h1>Players</h1>
			<table class="table">
				<thead >
					<tr>
						<th>Row Nr.</th>
						<th>Name</th>
						<th>Surname</th>
						<th>Team</th>
					</tr>
				</thead>
				<tbody>
				<?php $count = 1; foreach ($decoded as $page) {
					foreach ($page['data'] as $player){
						if($_GET['home_team'] === $player['team']['full_name'] 
						|| $_GET['visitor_team'] === $player['team']['full_name'] ){ ?>
							<tr>
								<td><?= $count++ ?></td>
								<td><?= $player['first_name'] ?></td>
								<td><?= $player['last_name'] ?></td>
								<td><?= $player['team']['full_name'] ?></td>
							</tr>
						<?php } 
					} 
				} ?>
				</tbody>
			</table>
			</div>
		</div>
	</body>
</html>

