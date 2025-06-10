<?php function generateRegisterForm(bool $isAdmin): void{ ?>

<form action="./api/handleRegister.php" method="POST">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" required />
    <label for="name">Nome</label>
    <input type="text" name="name" id="name" required />
    <label for="surname">Cognome</label>
    <input type="text" name="surname" id="surname" required />
    <label for="cellphone">Telefono</label>
    <input type="text" inputmode="numeric" pattern="\d*" name="cellphone" id="cellphone" required />
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required />
    <label for="passwordConfirm">Conferma Password</label>
    <input type="password" name="password" id="passwordConfirm" required />
    <?php if($isAdmin){?>
        <label for="token">Registration token</label>
        <input type="text" name="admin_token" id="token">
        <input type="hidden" name="type" id="type" value="2"/> 
        <input type="submit" value="Registrazione" class="admin" disabled>
    <?php } else { ?>
        <button id="typeSwitcher" type="button">
            <span></span>
        </button>
        <input type="hidden" name="type" value="0" />
        <input type="submit" value="Registrazione" disabled>
    <?php } ?>
</form>

<?php } ?>
