<?php //component for already reviewed reports 
    function storicalReport($report,$isProduct=false){
    
 ?> 
    <div class="report">
    <b>Revisionata il <?=$report["lastEdit"]?> da:</b>
    <?php if($isProduct): ?>
        <p>Prodotto Segnalato: <?=htmlspecialchars($report["nome"])?></p>
        <p>Venditore del prodotto: <?=htmlspecialchars($report["emailVenditore"])?></p>
        <?php endif?>

        <?php if(!$isProduct):?>
        <p>Venditore Segnalato: <?=htmlspecialchars($report["emailVenditore"])?></p>
    <?php endif?>
        <p>Segnalatore: <?php echo htmlspecialchars($report['emailSegnalatore'])?></p>
        <textarea><?php echo htmlspecialchars($report['motivo']); ?></textarea><br>
    </div>
<?php   } ?>