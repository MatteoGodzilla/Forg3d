<?php function create_header(){ ?>
<header>
	<div class="header-content">
		<a href="/"><h1>Forg3d</h1></a>
        <?php if (utenteLoggato()){ ?>
            <div id="userProfile">
                <span class="material-symbols-outlined">notifications</span>
                <a id="logout" href="./api/handleLogout.php">Logout</a>
            </div>
        <?php } else { ?>
            <a id="login" href="./login.php">Login</a>
        <?php } ?>
	</div>
</header>
<?php } ?>
