<?php function generateEditImage($img){ ?>
    <div class="immagine-preview">
        <img src="<?= htmlspecialchars($img['nomeFile']) ?>" alt="immagine prodotto"/>
        <input type="checkbox" name="deletedImages[]" id="deletedImages[<?= $img['id'] ?>]" value="<?= $img['id'] ?>"/>
        <label for="deletedImages[<?= $img['id'] ?>]" >Elimina</label>
    </div>
<?php } ?>
