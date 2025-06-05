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
        <a href="../cart.php"><span class="material-symbols-outlined">shopping_cart</span></a>
    <?php }?>
     <?php if (getUserType()==UserType::SELLER->value){ ?>
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

        if(getUserType()==UserType::SELLER->value){
            $query = "SELECT COUNT(id) as tot FROM Notifica WHERE emailVenditore = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $notifs = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $notifs[0]["tot"];
        }
        return 0;
    }
?>