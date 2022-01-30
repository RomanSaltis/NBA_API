<?php
$curl = curl_init();
$response = "./games.json";

if(file_get_contents($response) == false){
$response=[];
foreach(range(1, 5) as $i){
curl_setopt_array($curl, [
	CURLOPT_URL => "https://free-nba.p.rapidapi.com/games?page=$i&per_page=100",
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

if(!file_exists("./games.json")){
        file_put_contents("./games.json", "[]");
        file_put_contents("./id.json", 0);
}

function setData($response){
    file_put_contents('./games.json', json_encode($response));
}

if ($err) {
	echo "cURL Error #:" . $err;
} else {
    setData($response);
}
}

$decoded = json_decode(file_get_contents('./games.json'), true);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Saltis REST API Client</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
            <div class="p-3 mb-2 bg-light text-dark">
                <h1>Games</h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <form action="" method="get">
                                    <div class="row">
                                        <div class="col">
                                            <input class="form-control" type="text" name="search">
                                        </div>
                                        <div class="col">
                                            <select class="form-control" name="data">
                                            <option value="date">Date</option>
                                            <option value="home_team">Home team</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <input class="btn btn-primary" type="submit" value="search">
                                        </div>
                                    </div>
                                </form>
                            </tr>
                            <tr>
                                <th>Nr</th>
                                <th>Game date</th>
                                <th>Home team</th>
                                <th>Visitor team</th>
                                <th>Status</th>
                                <th>Home score</th>
                                <th>Visitor score</th>
                                <th>Home Conference</th>
                                <th>Visitor Conference</th>
                                <th>Players</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; foreach ($decoded as $page){
                                foreach ($page['data'] as $game){
                                    if ($game['home_team_score'] > $game['visitor_team_score'] &&
                                        $game['home_team']['conference'] === $game['visitor_team']['conference'] ){ 
                                        if (isset($_GET['data']) && $_GET['data'] == 'date'){
                                            if($_GET['search'] == substr($game['date'],0,10)){?>
                                                <tr>
                                                    <td><?= ++$count?></td>
                                                    <td><?= substr($game['date'],0,10) ?></td>
                                                    <td><?= $game['home_team']['full_name'] ?></td>
                                                    <td><?= $game['visitor_team']['full_name'] ?></td>
                                                    <td><?= $game['status'] ?></td>
                                                    <td><?= $game['home_team_score'] ?></td>
                                                    <td><?= $game['visitor_team_score'] ?></td>
                                                    <td><?= $game['home_team']['conference'] ?></td>
                                                    <td><?= $game['visitor_team']['conference'] ?></td>
                                                    <td>
                                                        <form action="players.php" method="get">
                                                            <input type="hidden" name="home_team" value="<?= $game['home_team']['full_name'] ?>">
                                                            <input type="hidden" name="visitor_team" value="<?= $game['visitor_team']['full_name'] ?>">
                                                            <input type="hidden" name="date" value="<?= substr($game['date'],0,10) ?>">
                                                            <input type="submit" class="btn btn-success" value="players">
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } 
                                        } 
                                        elseif (isset($_GET['data']) && $_GET['data'] == 'home_team'){
                                            if($_GET['search'] == $game['home_team']['full_name']){?>
                                                <tr>
                                                    <td><?= ++$count?></td>
                                                    <td><?= substr($game['date'],0,10) ?></td>
                                                    <td><?= $game['home_team']['full_name'] ?></td>
                                                    <td><?= $game['visitor_team']['full_name'] ?></td>
                                                    <td><?= $game['status'] ?></td>
                                                    <td><?= $game['home_team_score'] ?></td>
                                                    <td><?= $game['visitor_team_score'] ?></td>
                                                    <td><?= $game['home_team']['conference'] ?></td>
                                                    <td><?= $game['visitor_team']['conference'] ?></td>
                                                    <td>
                                                        <form action="players.php" method="get">
                                                            <input type="hidden" name="home_team" value="<?= $game['home_team']['full_name'] ?>">
                                                            <input type="hidden" name="visitor_team" value="<?= $game['visitor_team']['full_name'] ?>">
                                                            <input type="hidden" name="date" value="<?= substr($game['date'],0,10) ?>">
                                                            <input type="submit" class="btn btn-success" value="players">
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } 
                                        } 
                                        else { ?>
                                            <tr>
                                                <td><?= ++$count?></td>
                                                <td><?= substr($game['date'],0,10) ?></td>
                                                <td><?= $game['home_team']['full_name'] ?></td>
                                                <td><?= $game['visitor_team']['full_name'] ?></td>
                                                <td><?= $game['status'] ?></td>
                                                <td><?= $game['home_team_score'] ?></td>
                                                <td><?= $game['visitor_team_score'] ?></td>
                                                <td><?= $game['home_team']['conference'] ?></td>
                                                <td><?= $game['visitor_team']['conference'] ?></td>
                                                <td>
                                                     <form action="players.php" method="get">
                                                        <input type="hidden" name="home_team" value="<?= $game['home_team']['full_name'] ?>">
                                                        <input type="hidden" name="visitor_team" value="<?= $game['visitor_team']['full_name'] ?>">
                                                        <input type="hidden" name="date" value="<?= substr($game['date'],0,10) ?>">
                                                        <input type="submit" class="btn btn-success" value="players">
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } 
                                    } 
                                } 
                            } ?>
                        </tbody>
                    </table>
            </div>
        </div>
	</body>
</html>

