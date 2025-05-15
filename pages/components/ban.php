<?php //component for already reviewed reports 
    function showBan($banned,$isProduct=false){
    
 ?> 
    <div>
    <?php if($isProduct){ ?>
        <p>Prodotto Bandito: <?=$banned["nome"]?></p>
        <p>Venditore del prodotto: <?=$banned["emailVenditore"]?></p>
    <?php }?>

    <?php if(!$isProduct){?>
        <p>Venditore Bandito: <?=$banned["emailUtente"]?></p>
    <?php }?>

    </div>
<?php   } ?>