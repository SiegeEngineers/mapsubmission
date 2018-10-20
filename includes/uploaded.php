<h1 class="text-center">Map Contest</h1>

<h2>
    <small class="text-muted">Stage</small>
    <?php echo $stage; ?>
</h2>

<h3 class="text-center">Submission</h3>

<div class="alert alert-success">Your map has been submitted.</div>

<div class="form-group">
    <label for="inputAuthorName">Your name</label>
    <input type="text" class="form-control" id="inputAuthorName" name="authorName" aria-describedby="authorNameHelp"
           value="<?php echo htmlspecialchars($authorName); ?>" disabled>
</div>

<div class="form-group">
    <label for="inputMapName">Map name</label>
    <input type="text" class="form-control" id="inputMapName" name="mapName" aria-describedby="mapNameHelp"
           value="<?php echo htmlspecialchars($mapName); ?>" disabled>
</div>

<div class="form-group">
    <label for="inputDescription">Description</label>
    <textarea class="form-control" id="inputDescription" name="description" rows="3"
              aria-describedby="descriptionHelp"
              disabled><?php echo htmlspecialchars($description); ?></textarea>
</div>

<div class="form-group">
    <label for="inputGameVersion">Game version</label>
    <input type="text" class="form-control" id="inputGameVersion" name="gameVersion" aria-describedby="gameVersionHelp"
           value="<?php echo htmlspecialchars($gameVersion); ?>" disabled>
</div>

<div class="form-group">
    <label for="inputInstructions">Instructions</label>
    <textarea class="form-control" id="inputInstructions" name="instructions" rows="3"
              aria-describedby="instructionsHelp"
              disabled><?php echo htmlspecialchars($instructions); ?></textarea>
</div>

<div class="form-group">
    <label for="fileInputRms">Random Map Script</label>
    <input type="text" class="form-control" id="inputMapName" name="mapName" aria-describedby="mapNameHelp"
           value="<?php echo htmlspecialchars($filename); ?>" disabled>
</div>