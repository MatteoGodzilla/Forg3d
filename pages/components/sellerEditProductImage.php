<?php function generateEditImage($img){ ?>
    <div class="immagine-preview">
        <img alt="immagine prodotto" src="<?= htmlspecialchars($img['nomeFile']) ?>" />
        <input type="checkbox" name="deletedImages[]" id="deletedImages[<?= $img['id'] ?>]" value="<?= $img['id'] ?>"/>
        <label for="deletedImages[<?= $img['id'] ?>]" >Elimina</label>
    </div>
<?php } ?>
