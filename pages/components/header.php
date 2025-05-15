<?php function create_header(){ ?>
<header>
    <h1>Forg3d</h1>,
    <?php if (utenteLoggato()): ?>
                <p>Ciao <?= getSessionEmail(); ?> </p>
				<a href="./api/handleLogout.php">Logout</a>
				<?php if(getUserType() == UserType::SELLER->value){?>
					<a href="./api/handleLogout.php">carrello</a>	
				<?php } ?>

			<?php else: ?>
				<a href="./login.php">Login</a>
	<?php endif; ?>
</header>
<?php } ?>