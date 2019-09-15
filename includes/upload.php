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

    <h3>Submission</h3>
</div>

<?php
if ($error != '') {
    ?>
    <div class='alert alert-warning' role='alert'>
        <?php echo $error; ?>
    </div>
    <?php
}
?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="inputAuthorName">Who are you?
            <small id="authorNameHelp" class="form-text">Enter your name. Preferrably your AoEZone username.</small>
        </label>
        <input type="text" class="form-control" id="inputAuthorName" name="authorName"
               aria-describedby="authorNameHelp" placeholder=""
               value="<?php if (isset($_POST['authorName'])) {
                   echo htmlspecialchars($_POST['authorName']);
               } ?>">
    </div>

    <div class="form-group">
        <label for="fileInputRms">Random Map Script
            <small id="rmsHelp" class="form-text">Select your <code>.rms</code> file for upload
            </small>
        </label>
        <input type="file" class="form-control-file" id="fileInputRms" aria-describedby="rmsHelp"
               name="rms">
    </div>

    <div class="form-group">
        <label for="inputMapName">Map name
            <small id="mapNameHelp" class="form-text">Tell us the name of your map.</small>
        </label>
        <input type="text" class="form-control" id="inputMapName" name="mapName"
               aria-describedby="mapNameHelp" placeholder="Arabia"
               value="<?php if (isset($_POST['mapName'])) {
                   echo htmlspecialchars($_POST['mapName']);
               } ?>">
    </div>

    <div class="form-group">
        <label for="inputDescription">Description
            <small id="descriptionHelp" class="form-text">Describe your map in two to three
                sentences.
            </small>
        </label>
        <textarea class="form-control" id="inputDescription" name="description" rows="3"
                  aria-describedby="descriptionHelp"><?php if (isset($_POST['description'])) {
                echo htmlspecialchars($_POST['description']);
            } ?></textarea>
    </div>

    <div class="form-group">
        <label for="inputInstructions">Instructions
            <small id="instructionsHelp" class="form-text">If there are important things to know when playing
                this map, like special effects or win conditions, this information goes right here.
            </small>
        </label>
        <textarea class="form-control" id="inputInstructions" name="instructions" rows="3"
                  aria-describedby="instructionsHelp"><?php if (isset($_POST['instructions'])) {
                echo htmlspecialchars($_POST['instructions']);
            } ?></textarea>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit">Submit map!</button>
    </div>

</form>