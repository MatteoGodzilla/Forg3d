<?php function create_header(){ ?>
<header>
	<div class="header-content">
		<a href="/"><h1>Forg3d</h1></a>
        <?php if (utenteLoggato()){ ?>
            <div id="userProfile">
                <span class="material-symbols-outlined">person</span>
                <?php generateSymbol(); ?>
                <span id="notifications" class="material-symbols-outlined">notifications</span>
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
        <a><span class="material-symbols-outlined">shopping_cart</span></a>
    <?php }?>
     <?php if (getUserType()==UserType::SELLER->value){ ?>
        <a href="./../sellerHome.php"><span class="material-symbols-outlined">home</span></a>
    <?php }?>
    <?php if (getUserType()==UserType::ADMIN->value){ ?>
        <a href="./../adminHome.php"><span class="material-symbols-outlined">home</span></a>
    <?php }?>
<?php } ?>
