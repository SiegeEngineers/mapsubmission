<?php

function getScores($judging, $hash)
{

    $scores = [];
    $counts = [];

    foreach ($judging as $judge => $judgements) {
        if (isset($judgements[$hash])) {
            foreach ($judgements[$hash] as $key => $value) {
                if (!isset($scores[$key])) {
                    $scores[$key] = 0;
                    $counts[$key] = 0;
                }
                if ($value !== "") {
                    $scores[$key] += intval($value);
                    $counts[$key]++;
                }
            }
        }
    }
    foreach ($scores as $key => $value) {
        if ($counts[$key] !== 0) {
            $scores[$key] /= $counts[$key];
        }
    }

    return $scores;
}

function sumScores($scores)
{
    $sum = 0;
    foreach ($scores as $key => $value) {
        $sum += $value;
    }
    return $sum;
}

?>
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

        <h3>Results</h3>
    </div>
<?php


$json_string_2 = file_get_contents("data/judging.json");
$judging = json_decode($json_string_2, true);

$tbs = [];
$top_score = 0;

foreach ($participants as $name => $participant) {
    if (isset($participant['maps'])) {
        $map = $participant['maps'][0];
        $scores = getScores($judging, $map['hash']);
        $total = sumScores($scores);
        $participant['scores'] = $scores;
        $participant['total'] = $total;
        $tbs[$name] = $total;
        $top_score = max($top_score, $total);
    }
}

arsort($tbs);

foreach ($tbs as $name => $total) {
    $map = $participants[$name]['maps'][0];
    ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <?php
                echo htmlspecialchars($map['mapName']);
                if ($total === $top_score) {
                    echo ' <span class="badge badge-pill badge-primary">1st Place</span>';
                } ?>
            </h5>
            <h6 class="card-subtitle mb-2 text-muted">
                by <?php echo htmlspecialchars($name); ?> – <?php echo number_format($total, 1); ?> Points</h6>
            <p class="card-text"><?php echo htmlspecialchars($map['description']); ?></p>
            <?php if ($map['instructions'] !== '') {
                echo "<p class='card-text text-muted'>" . htmlspecialchars($map['instructions']) . "</p>\n";
            }
            ?>
            <span class="text-muted"><?php echo htmlspecialchars($map['gameVersion']); ?> –</span>
            <a href="<?php echo "maps/{$map['hash']}/" . htmlspecialchars($map['filename']); ?>"
               class="card-link">Download</a>
        </div>
    </div>

    <?php
}

?>