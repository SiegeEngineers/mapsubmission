<div class="text-center">
    <h1>Map Contest</h1>
    <h2>
        <small class="text-muted">Stage</small>
        Competitive map
    </h2>

    <h5>
        <small class="text-muted">Start</small>
        <?php echo $start->format("l, j. F Y, H:i e") ?>
    </h5>
    <h5>
        <small class="text-muted">Submission deadline</small>
        <?php echo $end->format("l, j. F Y, H:i e") ?>
    </h5>

    <h3>Submissions</h3>
</div>
<?php

foreach ($participants as $name => $participant) {
    if (isset($participant['maps'])) {
        $map = $participant['maps'][0];
        ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($map['mapName']); ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    by <?php echo htmlspecialchars($name); ?></h6>
                <p class="card-text"><?php echo htmlspecialchars($map['description']); ?></p>
                <?php if ($map['instructions'] !== '') {
                    echo "<p class='card-text text-muted'>" . htmlspecialchars($map['instructions']) . "</p>";
                }
                ?>
                <a href="<?php echo "data/{$map['hash']}/" . htmlspecialchars($map['filename']); ?>"
                   class="card-link">Download</a>
            </div>
        </div>

        <?php
    }
}

?>