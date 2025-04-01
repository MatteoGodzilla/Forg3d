<?php function generateLoginForm(bool $isAdmin): void{ ?>

<form action="./api/handleLogin.php" method="POST">
    <label for="email">Email</label>
    <input type="text" name="email" id="email" />
    <label for="password">Password</label>
    <input type="password" name="password" id="password" />
    <?php if(!$isAdmin){ ?>
        <select name="type" id="type">
            <option value="0" >Compratore</option>
            <option value="1" >Venditore</option>
            <!-- There should be another separate login form for admins that is not easily accessible -->
            <!-- <option value="2" >Admin</option> -->
        </select>
    <?php } else { ?>
        <input type="hidden" name="type" value="2" />
    <?php } ?>
    <input type="submit" value="Login">
</form>

<?php } ?>