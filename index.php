<?php

include('includes/config.php');
$now = new DateTime('now');
$start->setTimezone(new DateTimeZone("UTC"));
$end->setTimezone(new DateTimeZone("UTC"));

$template = 'includes/main.php';
$error = '';
$json_string = file_get_contents("data/participants.json");
$participants = json_decode($json_string, true);
if ($now > $end) {
    $template = 'includes/list.php';
} elseif ($now > $start && $now < $end) {
    $template = 'includes/upload.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $error = upload($participants);
        if ($error === "") {
            $template = 'includes/uploaded.php';
            $authorName = $_POST['authorName'];
            $mapName = $_POST['mapName'];
            $description = $_POST['description'];
            $instructions = $_POST['instructions'];
            $gameVersion = $_POST['gameVersion'];
            $filename = $_FILES['rms']['name'];
        }
    }
}

function upload($participants)
{
    if (isset($_POST['authorName']) &&
        isset($_POST['mapName']) &&
        isset($_POST['description']) &&
        isset($_POST['instructions']) &&
        isset($_POST['gameVersion']) &&
        isset($_FILES['rms'])
    ) {
        if ($_POST['authorName'] === '' ||
            $_POST['mapName'] === '' ||
            $_POST['description'] === '' ||
            $_POST['gameVersion'] === '' ||
            $_FILES['rms']['name'] === ''
        ) {
            return 'Please fill in at least your name, the rms file, the map name, a description, and the game version.';
        }

        try {
            $bytes = random_bytes(5);
            $hash = bin2hex($bytes);

            $authorName = $_POST['authorName'];
            $map = [
                "authorName" => $authorName,
                "mapName" => $_POST['mapName'],
                "description" => $_POST['description'],
                "instructions" => $_POST['instructions'],
                "gameVersion" => $_POST['gameVersion'],
                "filename" => $_FILES['rms']['name'],
                "hash" => $hash
            ];
            if (!isset($participants[$authorName]['maps'])) {
                $participants[$authorName]['maps'] = [];
            }
            array_unshift($participants[$authorName]['maps'], $map);
            $target_dir = "data/$hash/";
            $target_file = $target_dir . basename($_FILES["rms"]["name"]);
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if ($fileType != "rms") {
                return "Please upload a .rms file";
            }

            $success = mkdir("data/$hash");
            if ($success === false) {
                return "Could not create folder";
            }

            if (!move_uploaded_file($_FILES["rms"]["tmp_name"], $target_file)) {
                return "There was an error uploading your file";
            }

            $success = file_put_contents("data/participants.json", json_encode($participants, JSON_PRETTY_PRINT));
            if ($success === false) {
                return "Could not save metadata";
            }
        } catch (Exception $e) {
            return 'Could not create ID.';
        }
    }
    return '';
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Map Contest</title>
    <link rel="stylesheet" type="text/css" href="assets/bootstrap.min.css">
    <style>
        html,
        body {
            height: 100%;
            background: url("assets/bg<?php echo rand(1,4); ?>.png") no-repeat;
            background-size: cover;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8);
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <?php

                include($template);

                ?>

            </div>
        </div>
    </div>
</div>
</body>
</html>
