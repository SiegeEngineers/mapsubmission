<div class="text-center">
    <h1>Map Contest</h1>

    <h2>
        <small class="text-muted">Stage</small>
        <?php echo $stage;?>
    </h2>

    <h5>
        <small class="text-muted">Start</small> <?php echo $start->format("l, j. F Y, H:i e") ?>
    </h5>

    <h5>
        <small class="text-muted">Submission deadline</small> <?php echo $end->format("l, j. F Y, H:i e") ?>
    </h5>
</div>
