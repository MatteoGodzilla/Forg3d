const email = document.querySelector("#email"); 
const password = document.querySelector("#password"); 
const submit = document.querySelector("input[type='submit']");

console.log(submit)

email.oninput = checkEnableLoginButton;
password.oninput = checkEnableLoginButton;

function checkEnableLoginButton(){
    let valid = email.value != "";
    valid = valid && password.value != "";
    valid = valid && email.checkValidity();

    console.log(valid);

    submit.disabled = !valid;
}

checkEnableLoginButton();
