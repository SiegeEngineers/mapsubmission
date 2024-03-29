<?php

function getScores($judging, $hash)
{

    $scores = [];
    $counts = [];

    foreach ($judging as $judge => $judgements) {
        if (isset($judgements[$hash])) {
            foreach ($judgements[$hash] as $key => $value) {
                if ($key !== 'Comments') {
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
            if (!isset($scores['Comments'])) {
                $scores['Comments'] = [];
            }
            $scores['Comments'][$judge] = $judgements[$hash]['Comments'];
        }
    }
    foreach ($scores as $key => $value) {
        if ($key !== 'Comments') {
            if ($counts[$key] !== 0) {
                $scores[$key] /= $counts[$key];
            }
        }
    }

    return $scores;
}

function sumScores($scores)
{
    $sum = 0;
    foreach ($scores as $key => $value) {
        if ($key !== 'Comments') {
            $sum += $value;
        }
    }
    return $sum;
}

?>
    <div class="text-center">
        <h1>Random Map Contest</h1>
        <h2>
            <?php echo $stage; ?>
        </h2>

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
        $participants[$name]['scores'] = $scores;
        $participants[$name]['total'] = $total;
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
                $mapName = htmlspecialchars($map['mapName']);
                $totalFormatted = number_format($total, 1);
                echo "$mapName <small>– $totalFormatted Points</small>";
                if ($total === $top_score) {
                    echo ' <span class="badge badge-pill badge-primary">1st Place</span>';
                } ?>
            </h5>
            <h6 class="card-subtitle mb-2 text-muted">
                by <?php echo htmlspecialchars($name); ?></h6>
            <p class="card-text"><?php echo htmlspecialchars($map['description']); ?></p>
            <?php if ($map['instructions'] !== '') {
                echo "<p class='card-text text-muted'>" . htmlspecialchars($map['instructions']) . "</p>\n";
            }
            ?>
            <p><a href="<?php echo "maps/{$map['hash']}/" . htmlspecialchars($map['filename']); ?>"
               class="card-link">Download rms</a></p>

            <?php if($map['image'] != null){ ?>
                <p class="text-center"><img style="max-width:100%;" src="<?php echo "maps/{$map['hash']}/" . htmlspecialchars($map['image']); ?>"/></p>
            <?php } else { ?>
                <p class="text-center">(no image)</p>
            <?php } ?>
            <hr>
            <table class="table table-borderless table-sm">
                <thead>
                <tr>
                    <?php
                    foreach ($categories as $category) {
                        echo "<th class='text-center' scope='col'>{$category['name']}</th>\n";
                    } ?>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php
                    foreach ($categories as $category) {
                        $key = toKey($category['name']);
                        $value = number_format($participants[$name]['scores'][$key], 1);
                        $max = number_format($category['max'], 1);
                        echo "<td class='text-center'>$value / $max</td>\n";
                    }
                    ?>
                </tr>
                </tbody>
            </table>
            <dl>
                <?php
                foreach ($participants[$name]['scores']['Comments'] as $judge => $comment) {
                    $judgeName = htmlspecialchars($judge);
                    $commentText = str_replace("\n","<br>\n",htmlspecialchars($comment));
                    if ($commentText !== "") {
                        echo "<dt>$judgeName</dt>\n";
                        echo "<dd class='font-italic'>$commentText</dd>\n";
                    }
                }
                ?>
            </dl>
        </div>
    </div>

    <?php
}

?>