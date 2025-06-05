<?php function create_header(){ ?>
<header>
	<div class="header-content">
		<a href="/"><h1>Forg3d</h1></a>
        <?php if (utenteLoggato()){?>
            <div id="userProfile">
                <?php generateSymbol(); ?>
                <a href="/notifications.php">
                    <div id="notifications">
                        <span id="notifications" class="material-symbols-outlined">notifications</span>
                        <?php if(($notifCount = getNotificationsCount()) > 0){ ?>
                            <span class="notification-badge"><?=$notifCount?></span>
                        <?php }?>
                    </div>
                </a>
                <a id="logout" href="./api/handleLogout.php">Logout</a>
            </div>
        <?php } else { ?>
            <div id="userProfile">
            <a id="login" href="./login.php">Login</a>
            </div>
        <?php } ?>
	</div>
</header>
<?php } ?>

<?php function generateSymbol(){ ?>
    <?php if (getUserType()==UserType::BUYER->value){ ?>
        <span class="material-symbols-outlined">receipt_long</span>
        <a href="../cart.php"><span class="material-symbols-outlined">shopping_cart</span></a>
    <?php }?>
     <?php if (getUserType()==UserType::SELLER->value){ ?>
        <a href="./../orders.php"><span class="material-symbols-outlined">receipt_long</span></a>
        <a href="./../sellerHome.php"><span class="material-symbols-outlined">home</span></a>
    <?php }?>
    <?php if (getUserType()==UserType::ADMIN->value){ ?>
        <a href="./../adminHome.php"><span class="material-symbols-outlined">home</span></a>
    <?php }?>
<?php } ?>


<?php
    function getNotificationsCount(){
    require_once("../php/db.php");
    global $connection;
    $email = getSessionEmail();
    switch(getUserType()){
        case UserType::BUYER->value:
            $query_notifiche = "SELECT COUNT(id) AS tot FROM Notifica WHERE emailVenditore in 
            (SELECT emailVenditore FROM Follow WHERE emailCompratore=?)
            ORDER BY creazione DESC";
            $stmt = mysqli_prepare($connection, $query_notifiche);
            mysqli_stmt_bind_param($stmt, "s", $email);
            break;
        case UserType::SELLER->value:
            $query_notifiche = "SELECT COUNT(id) AS tot FROM Notifica WHERE emailVenditore is null AND id NOT in
            (SELECT idNotifica FROM NotificaLetta WHERE emailCompratore=?)
            ORDER BY creazione DESC";
            $stmt = mysqli_prepare($connection, $query_notifiche);
            mysqli_stmt_bind_param($stmt, "s", $email);
            break;
        case UserType::ADMIN->value:
            $query_notifiche = "SELECT COUNT(id) AS tot FROM Notifica WHERE emailVenditore is null AND id NOT in
            (SELECT idNotifica FROM NotificaLetta WHERE emailCompratore=?)
            ORDER BY creazione DESC";
            $stmt = mysqli_prepare($connection, $query_notifiche);
            mysqli_stmt_bind_param($stmt, "s", $email);
            break;
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $notifs = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $notifs[0]["tot"];
    }
?>