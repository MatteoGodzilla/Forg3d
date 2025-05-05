<?php function generateLoginForm(bool $isAdmin): void{ ?>

<form action="./api/handleLogin.php" method="POST">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" />
    <label for="password">Password</label>
    <input type="password" name="password" id="password" />
    <?php if(!$isAdmin){ ?>
<!--        
        <select name="type" id="type">
            <option value="0" >Compratore</option>
            <option value="1" >Venditore</option>
        </select>
--!>
        <button id="typeSwitcher" type="button">
            <div></div>
        </button>
        <input type="hidden" name="type" value="0" />
    <?php } else { ?>
        <input type="hidden" name="type" value="2" />
    <?php } ?>
    <input type="submit" value="Login">
</form>

<?php } ?>
