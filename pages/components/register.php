<?php function generateRegisterForm(int $userType): void{ ?>

<form action="./api/handleRegister.php" method="POST">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" />
    <label for="surname">Surname</label>
    <input type="text" name="surname" id="surname" />
    <label for="email">Email</label>
    <input type="text" name="email" id="email" />
    <label for="cellphone">Telefono</label>
    <input type="text" name="cellphone" id="cellphone" />
    <label for="password">Password</label>
    <input type="password" name="password" id="password" />
    <input type= "hidden" name="type" id="type" value="<?php echo $userType?>"/> 
    <?php if($userType==2){?>
        <label for="token">Registration token</label>
        <input type = "text" name="admin_token" id="token">
    <?php } ?>
    <input type="submit" value="Login">
</form>

<?php } ?>