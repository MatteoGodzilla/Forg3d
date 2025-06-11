<?php function varianteOption($variante, bool $showVariant = true){ ?>
<div class="variantOption">
    <?php if($showVariant){ ?>
        <input name="choice" type="radio" value="<?=$variante["id"]?>"/>
    <?php } ?>
    <div>
        <span><?php echo htmlspecialchars($variante['nomeColore'])." ( ".number_format($variante["prezzo"]/100, 2)."€ )"; ?></span>
        <small><?php echo htmlspecialchars($variante['tipologia']); ?></small>
    </div>
    <svg width="40" height="40px">
        <ellipse stroke="black" fill="#<?= htmlspecialchars($variante["hexColore"])?>" stroke-width="2" rx="16" ry="16" cx="20" cy="20"></ellipse>
    </svg>
    <!-- This is not the best way, but it's the best i can come up with -->
    <input type="hidden" value="#<?= htmlspecialchars($variante["hexColore"])?>" />
</div>
<?php } ?>
