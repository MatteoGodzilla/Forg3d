<?php function create_header(){ ?>
<header>
	<div class="header-content">
		<a href="/"><h1>Forg3d</h1></a>
        <?php if (utenteLoggato()){?>
            <div id="userProfile">
                <?php generateSymbol(); ?>
                <a href="/notifications.php">
                    <div id="notifications">
                        <span class="material-symbols-outlined">notifications</span>
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
        <div id="orders">
            <a href="./../buyersOrders.php" ><span class="material-symbols-outlined">receipt_long</span></a>
            <?php if(($orderCount = getOrdersCount()) > 0){ ?>
                <span class="notification-badge"><?=$orderCount?></span>
            <?php }?>
        </div>
        <a href="../cart.php"><span class="material-symbols-outlined">shopping_cart</span></a>
    <?php }?>
     <?php if (getUserType()==UserType::SELLER->value){ ?>
        <div id="orders">
            <a href="./../sellerOrders.php"><span class="material-symbols-outlined">receipt_long</span></a>
            <?php if(($orderCount = getOrdersCount()) > 0){ ?>
                    <span class="notification-badge"><?=$orderCount?></span>
            <?php }?>
        </div>
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
            $query_notifiche = "SELECT COUNT(id) AS tot FROM Notifica WHERE 
            ((emailMittente in (SELECT emailVenditore FROM Follow WHERE emailCompratore=?) AND emailDestinatario is NULL) OR ( emailMittente is NULL AND emailDestinatario is NULL) OR emailDestinatario = ?)  AND
            id NOT in (SELECT idNotifica FROM NotificaLetta WHERE destinatario=?)
            ORDER BY creazione DESC";
            $stmt = mysqli_prepare($connection, $query_notifiche);
            mysqli_stmt_bind_param($stmt, "sss", $email,$email,$email);
            break;
        case UserType::SELLER->value:
            $query_notifiche = "SELECT COUNT(id) AS tot FROM Notifica WHERE 
            emailMittente is null AND 
            (emailDestinatario is NULL OR emailDestinatario = ?) AND
            id NOT in (SELECT idNotifica FROM NotificaLetta WHERE destinatario=?)";
            $stmt = mysqli_prepare($connection, $query_notifiche);
            mysqli_stmt_bind_param($stmt, "ss", $email,$email);
            break;
        case UserType::ADMIN->value:
            $query_notifiche = "SELECT COUNT(id) AS tot FROM Notifica WHERE emailMittente is null AND
            (emailDestinatario is NULL OR emailDestinatario = ?) AND
             id NOT in (SELECT idNotifica FROM NotificaLetta WHERE destinatario=?)";
            $stmt = mysqli_prepare($connection, $query_notifiche);
            mysqli_stmt_bind_param($stmt, "ss", $email,$email);
            break;
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $notifs = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $notifs[0]["tot"];
    }
?>

<?php
    function getOrdersCount(){
    require_once("../php/db.php");
    global $connection;
    $email = getSessionEmail();
    switch(getUserType()){
        case UserType::BUYER->value:
            $query_ordini = "SELECT COUNT(id) AS tot FROM Ordine WHERE emailCompratore = ? AND stato = 1";
            $stmt = mysqli_prepare($connection, $query_ordini);
            mysqli_stmt_bind_param($stmt, "s", $email);
            break;
        case UserType::SELLER->value:
            $query_ordini = "SELECT COUNT(id) AS tot FROM Ordine WHERE emailVenditore = ? AND stato = 0";
            $stmt = mysqli_prepare($connection, $query_ordini);
            mysqli_stmt_bind_param($stmt, "s", $email);
            break;
        case UserType::ADMIN->value:
            return 0;
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $orders[0]["tot"];
    }
?>