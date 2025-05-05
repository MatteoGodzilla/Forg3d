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
        <p><?php echo ($materiale['nomeColore']); ?> (<?php echo ($materiale['tipologia']); ?>) </p>
        <p>#<?php echo ($materiale['hexColore']); ?></p>
        <div>
            <a href="#">Modifica</a>
            <a href="#">Elimina</a>
        </div>
    </div>
</article>
<?php } ?>
