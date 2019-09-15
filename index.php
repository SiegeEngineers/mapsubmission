<?php

include('includes/config.php');
include('includes/helpers.php');
$now = new DateTime('now');
$start->setTimezone(new DateTimeZone("UTC"));
$end->setTimezone(new DateTimeZone("UTC"));

$template = 'includes/main.php';
$error = '';
$json_string = file_get_contents("data/participants.json");
$participants = json_decode($json_string, true);
if ($now > $end) {
    if ($published) {
        $template = 'includes/list.php';
    } else {
        $template = 'includes/judging.php';
    }
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
            $filename = $_FILES['rms']['name'];
            $directory = $participants[$authorName]['maps'][0]['hash'];
            $submissionCode = substr(hash('sha256', $directory), 0, 6);
        }
    }
}

function upload(&$participants)
{
    if (isset($_POST['authorName']) &&
        isset($_POST['mapName']) &&
        isset($_POST['description']) &&
        isset($_POST['instructions']) &&
        isset($_FILES['rms'])
    ) {
        if ($_POST['authorName'] === '' ||
            $_POST['mapName'] === '' ||
            $_POST['description'] === '' ||
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
                "filename" => $_FILES['rms']['name'],
                "hash" => $hash
            ];
            if (!isset($participants[$authorName]['maps'])) {
                $participants[$authorName]['maps'] = [];
            }
            array_unshift($participants[$authorName]['maps'], $map);
            $target_dir = "maps/$hash/";
            $target_file = $target_dir . basename($_FILES["rms"]["name"]);
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if ($fileType != "rms") {
                return "Please upload a .rms file";
            }

            $success = mkdir($target_dir);
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Random Map Contest</title>
    <link rel="stylesheet" type="text/css" href="assets/dark-bootstrap.min.css">
    <style>
        html,
        body {
            height: 100%;
            background: url("assets/bg<?php echo rand(1,4); ?>.png") no-repeat fixed;
            background-size: cover;
        }

        .card {
            background-color: rgba(48, 48, 48, 0.8);
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h5 > .badge {
            vertical-align: middle;
            margin-top: -0.5em;
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
