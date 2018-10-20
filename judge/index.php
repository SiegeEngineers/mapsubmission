<?php

include('../includes/config.php');
$now = new DateTime('now');
$start->setTimezone(new DateTimeZone("UTC"));
$end->setTimezone(new DateTimeZone("UTC"));

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    die('Who are you???');
}

$username = $_SERVER['PHP_AUTH_USER'];

$error = '';
$json_string = file_get_contents("../data/participants.json");
$json_string_2 = file_get_contents("../data/judging.json");
$participants = json_decode($json_string, true);
$judging = json_decode($json_string_2, true);


$categories = [
    ['name' => 'Originality', 'min' => 0, 'max' => 4],
    ['name' => 'Professionality', 'min' => 0, 'max' => 2],
    ['name' => 'Balance & Playability', 'min' => 0, 'max' => 4],
    ['name' => 'Theme', 'min' => 0, 'max' => 4],
    ['name' => 'Visual Appeal', 'min' => 0, 'max' => 4]
];


if (!$published && $now > $end && $_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST);
    $error = save($judging, $categories, $username);
}


function save($judging, $categories, $username)
{
    if (!isset($judging[$username])) {
        $judging[$username] = [];
    }
    $count = 0;
    foreach ($_POST as $key => $value) {
        foreach ($categories as $category) {
            $needle = toKey($category['name']);
            if (strpos($key, $needle) === 0) {
                $count++;
                $hash = substr($key, strlen($needle));
                if (!isset($judging[$username][$hash])) {
                    $judging[$username][$hash] = [];
                }
                $judging[$username][$hash][$needle] = $value;
            }
        }
    }

    $success = file_put_contents("../data/judging.json", json_encode($judging, JSON_PRETTY_PRINT));
    if ($success === false) {
        return "Could not save metadata";
    }
    return '' . $count;
}

function toKey($string)
{
    return preg_replace("/[^A-Za-z]/", "", $string);
}

function rating($hashvalue, $categories, $judging, $username)
{

    $retval = "
    <div class='form'>
    <div class='row'>
        <div class='col-2'>
            <h5 class='card-title'>Rate this map!</h5>
        </div>";

    foreach ($categories as $category) {
        $key = toKey($category['name']);
        $value = '';
        if (isset($judging[$username]) &&
            isset($judging[$username][$hashvalue]) &&
            isset($judging[$username][$hashvalue][$key])) {
            $value = $judging[$username][$hashvalue][$key];
        }
        $retval .= "
        <div class='col-2'>
            <div class='form-group'>
                <label for='input$key$hashvalue'>{$category['name']}</label>
                <div class='input-group mb-2 mr-sm-2'>
                    <div class='input-group-prepend'>
                        <div class='input-group-text'>({$category['min']}-{$category['max']})</div>
                    </div>
                    <input type='number'
                           class='form-control'
                           id='input$key$hashvalue'
                           name='$key$hashvalue'
                           min='{$category['min']}'
                           max='{$category['max']}'
                           value='$value'>
                </div>
            </div>
        </div>";
    }

    $retval .= "</div></div>";

    return $retval;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Random Map Contest</title>
    <link rel="stylesheet" type="text/css" href="../assets/dark-bootstrap.min.css">
    <style>
        html,
        body {
            height: 100%;
            background: url("../assets/bg<?php echo rand(1,4); ?>.png") no-repeat fixed;
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
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h1>Random Map Contest</h1>
                    <h2>
                        <small class="text-muted">Stage</small>
                        <?php echo $stage; ?>
                    </h2>

                    <h5>
                        <small class="text-muted">Start</small>
                        <?php echo $start->format("l, j. F Y, H:i e") ?>
                    </h5>
                    <h5>
                        <small class="text-muted">Submission deadline</small>
                        <?php echo $end->format("l, j. F Y, H:i e") ?>
                    </h5>

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if ($error === '') {
                            echo '<div class="alert alert-success">Saved!</div>';
                        } else {
                            echo '<div class="alert alert-danger"><strong>Error:</strong> ' . $error . '</div>';
                        }
                    }

                    if ($published){ ?>

                        <p class="mt-5">Judging has finished. Thank you!</p>

                    <?php } else { ?>

                    <h3 class="mt-4">Judging: <?php echo $username; ?></h3>
                </div>
                <form method="POST">
                    <?php

                    foreach ($participants as $name => $participant) {
                        if (isset($participant['maps'])) {
                            $map = $participant['maps'][0];
                            ?>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($map['mapName']); ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        by <?php echo htmlspecialchars("???"); ?></h6>
                                    <p class="card-text"><?php echo htmlspecialchars($map['description']); ?></p>
                                    <?php if ($map['instructions'] !== '') {
                                        echo "<p class='card-text text-muted'>" . htmlspecialchars($map['instructions']) . "</p>";
                                    }
                                    ?>
                                    <span class="text-muted"><?php echo htmlspecialchars($map['gameVersion']); ?>
                                        –</span>
                                    <a href="<?php echo "data/{$map['hash']}/" . htmlspecialchars($map['filename']); ?>"
                                       class="card-link">Download</a>

                                    <hr>

                                    <?php echo rating($map['hash'], $categories, $judging, $username); ?>

                                </div>

                            </div>

                            <?php
                        }
                    } ?>

                    <p class="text-center">
                        <button class="btn btn-primary btn-lg">Save</button>
                    </p>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>