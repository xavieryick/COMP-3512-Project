<form method="POST" action="photos/unflag">
    <button type="submit" name="imageid" value="<?= $target_photo["ImageID"] ?>">Unflag</button>
</form>
<form method="POST" action="photos/delete">
    <button type="submit" name="imageid" value="<?= $target_photo["ImageID"] ?>">Delete</button>
</form>