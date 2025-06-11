<?php function userInfoForm($user): void{ ?>


<h2>I tuoi dati</h2>
<form action="./api/editUser.php" method="POST">
    <p>Email: <?=$user["email"]?></p>
    <label for="name">Nome</label>
    <input type="text" name="name" id="name" value ="<?=$user["nome"]?>" required />
    <label for="surname">Cognome</label>
    <input type="text" name="surname" id="surname" value ="<?=$user["cognome"]?>" required />
    <label for="cellphone">Telefono</label>
    <input type="text" inputmode="numeric" pattern="\d*" name="cellphone" value ="<?=$user["telefono"]?>" id="cellphone" required />
    <input type="submit" id = "<?=getIdFromUserType(getUserType())?>" value="Aggiorna">
</form>

<h2>Modifica Password</h2>
<form action="./api/editUserPassword.php" method="POST">
    <label for="password_old">Vecchia Password</label>
    <input type="password" name="password_old" id="password_old"  required />
    <label for="password_new">Nuova Password</label>
    <input type="password" name="password_new" id="password_new" required />
    <label for="repeat">Ripeti Nuova Password</label>
    <input type="password"  name="repeat"  id="repeat" required />
    <input id ="changePassword" type="submit" value="Aggiorna Password" disabled>
</form>


<?php } ?>


<?php function getIdFromUserType($type){
     if($type == UserType::BUYER->value) return "buyerButton";
     if($type == UserType::SELLER->value) return "sellerButton";
     return "adminButton";
}?>