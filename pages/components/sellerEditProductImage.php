<?php function generateEditImage($image){ ?>
    <div class="immagine-preview">
        <img src="<?= htmlspecialchars($img['nomeFile']) ?>" alt="immagine prodotto"/>
        <input type="checkbox" name="deletedImages[]" value="<?= $img['id'] ?>"/> Elimina
    </div>
<?php } ?>
