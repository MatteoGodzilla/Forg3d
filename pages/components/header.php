<?php function create_header(){ ?>
<header>
	<h1>Forg3d</h1>
    <?php if (utenteLoggato()){ ?>
				<div id="interactibles">
					<div id="userProfile"></div>
					<a id="logout" href="./api/handleLogout.php">Logout</a>
				</div>
			<?php }else{ ?>
				<a href="./login.php">Login</a>
	<?php } ?>
</header>
<?php } ?>