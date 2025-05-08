<?php function sellerRequest($request){ ?>
<article class="product">
    <div>
        <p><?php echo ($request['cognome']." ".$request['nome']); ?></p>
        <small><?php echo ($request['emailUtente']); ?></small><br>
        <small><?php echo ($request['telefono']); ?></small>
        <div>
            <a href="<?= "../api/acceptRequest.php?email=".$request["emailUtente"] ?>">Accetta</a>
            <a href="#">Rifiuta</a>
        </div>
    </div>
</article>
<?php } ?>
