<?php function sellerHomeMaterial($materiale){ ?>
<article class="material">
    <svg width="100px "height="100px"> 
        <title>Indicatore Colore <?php echo ($materiale['hexColore']); ?></title>
        <ellipse rx="46px" ry="46px" cx="50px" cy="50px" 
            stroke="black" stroke-width="4"
            fill="#<?php echo ($materiale['hexColore']); ?>" 
        /> 
    </svg>
    <div>
        <h3><?php echo ($materiale['nomeColore']); ?> (<?php echo ($materiale['tipologia']); ?>) </h3>
        <p>#<?php echo ($materiale['hexColore']); ?></p>
        <div>
            <a class ="button-create" href="editMaterial.php?id=<?= $materiale['id']?>"> Modifica</a>
            <a class ="button-delete" href="/api/deleteMaterial.php?id=<?= $materiale['id']?>">Elimina</a>
        </div>
    </div>
</article>
<?php } ?>
