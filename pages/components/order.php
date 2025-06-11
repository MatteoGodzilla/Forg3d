<?php
    function order($order){
        $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
?>
    <div class="order">
            <a href="product.php?id=<?=$order["idProdotto"]?>">
                    <?php if(isset($order["immagine"]) && $order["immagine"]!=null && file_exists($rootDir.$order["immagine"])) {?>
                        <img  alt = "foto prodotto" src="<?= $order["immagine"]?>"> </img>
                    <?php }else{?>
                        <img  alt = "immagine non trovata" src="https://placehold.co/800x600"> </img>
                    <?php }?>
            </a>
        <div>
            <h3><?=$order["nome"]?></h3>
            <p>Variante:<?=$order["variante"]?></p>
            <?php if(getUserType()==UserType::SELLER->value){?>
                <p>Richiedente: <?=$order["buyer"]?></p>
            <?php }?>
            <?php if(getUserType()==UserType::BUYER->value){?>
                <p>Comprato da: <?=$order["seller"]?></p>
            <?php }?>
            <p>Quantità richiesta:<?=$order["quantita"]?></p>
            <h3>Pagamento ricevuto: €<?= number_format($order["quantita"]*$order["prezzo"] / 100, 2); ?></h3>
            <?php if($order["stato"]==0 && getUserType()==UserType::SELLER->value){?>
                <a class="button-accept"  href="../api/advanceOrder.php?id=<?=$order["OrderId"]?>">Conferma spedizione</a>
            <?php }?>
            <?php if($order["stato"]==1 && getUserType()==UserType::BUYER->value){?>
                <a class="button-accept"  href="../api/advanceOrder.php?id=<?=$order["OrderId"]?>">Conferma arrivo</a>
            <?php }?>
        </div>
    </div>

<?php 
    }
