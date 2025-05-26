 
<?php function adminInterface ($pendingRequests,$sellerReports,$productReports){ ?>
        <p><?php echo "Richieste di approvazione in sospeso:".$pendingRequests ?></p>
        <a class="functional" href="checkAdmissionRequests.php">Vai alle richieste</a><br>
        <p><?php echo "Nuove segnalazioni di venditori:".$sellerReports ?></p>
        <a class="functional" href="checkSellerReports.php">Segnalazioni Venditori</a><br>
        <p><?php echo "Nuove segnalazioni di prodotti:".$productReports ?></p>
        <a class="functional" href="checkProductsReports.php">Segnalazioni Prodotti</a><br>
        <h2>Ban attivi</h2>
        <a class="functional" href="banHistory.php?Sellers">Utenti Banditi</a><br>
        <a class="functional" href="banHistory.php?Products">Prodotti Banditi</a><br>
        <h2>Storico segnalazioni</h2>
        <a class="functional" href="reportsHistory.php?Sellers">Storico Venditori</a><br>
        <a class="functional" href="reportsHistory.php?Products">Storico Prodotti</a><br>
        <h2>Notifiche</h2>
        <a class="functional" href="adminNotification.php">Invia notifica</a><br>
<?php }?>
