<?php function create_header(){ ?>
<header>
	<div class="header-content">
		<h1>Forg3d</h1>
			<?php if (utenteLoggato()){ ?>
					<div id="userProfile"></div>
					<a id="logout" href="./api/handleLogout.php">Logout</a>
					<?php }else{ ?>
						<a id="login" href="./login.php">Login</a>
			<?php } ?>
	</div>
</header>
<?php } ?>