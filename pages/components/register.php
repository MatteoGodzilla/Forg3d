<?php function generateRegisterForm(bool $isAdmin): void{ ?>

<form action="./api/handleRegister.php" method="POST">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" />
    <label for="name">Nome</label>
    <input type="text" name="name" id="name" />
    <label for="surname">Cognome</label>
    <input type="text" name="surname" id="surname" />
    <label for="cellphone">Telefono</label>
    <input type="text" inputmode="numeric" pattern="\d*" name="cellphone" id="cellphone" />
    <label for="password">Password</label>
    <input type="password" name="password" id="password" />
    <label for="passwordConfirm">Conferma Password</label>
    <input type="password" name="" id="passwordConfirm" />
    <?php if($isAdmin){?>
        <label for="token">Registration token</label>
        <input type="text" name="admin_token" id="token">
        <input type="hidden" name="type" id="type" value="2"/> 
    <?php } else { ?>
        <button id="typeSwitcher" type="button">
            <div></div>
        </button>
        <input type="hidden" name="type" value="0" />
    <?php } ?>
    <input type="submit" value="Registrazione" disabled>
</form>

<?php } ?>
