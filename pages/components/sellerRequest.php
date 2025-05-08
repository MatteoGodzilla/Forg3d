<?php function sellerRequest($request){ ?>
<article class="product">
    <div>
        <p><?php echo ($request['cognome']." ".$request['nome']); ?></p>
        <small><?php echo ($request['emailUtente']); ?></small><br>
        <small><?php echo ($request['telefono']); ?></small>
        <div>
            <a href="<?= "../api/acceptRequest.php?email=".$request["emailUtente"]."&accept=true" ?>">Accetta</a>
            <a href="<?= "../api/acceptRequest.php?email=".$request["emailUtente"]."&accept=false" ?>">Rifiuta</a>
        </div>
    </div>
</article>
<?php } ?>
