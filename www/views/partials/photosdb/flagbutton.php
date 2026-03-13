<form method="POST" action="photos/flag">
    <button type="submit" name="imageid" value="<?= $target_photo["ImageID"] ?>">Flag</button>
</form>
<form method="POST" action="photos/delete">
    <button type="submit" name="imageid" value="<?= $target_photo["ImageID"] ?>">Delete</button>
</form>