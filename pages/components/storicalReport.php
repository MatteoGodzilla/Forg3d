<?php //component for already reviewed reports 
    function storicalReport($report,$isProduct=false){
    
 ?> 
    <div>
    <b>Revisionata il <?=$report["ultimaModifica"]?> da:</b>
    <?php if($isProduct): ?>
        <p>Prodotto Segnalato: <?=$report["nome"]?></p>
        <p>Venditore del prodotto: <?=$report["emailVenditore"]?></p>
        <?php endif?>

        <?php if(!$isProduct):?>
        <p>Venditore Segnalato: <?=$report["emailVenditore"]?></p>
    <?php endif?>
        <p>Segnalatore: <?php echo ($report['emailSegnalatore'])?></p>
        <textarea><?php echo ($report['motivo']); ?></textarea><br>
    </div>
<?php   } ?>