const email = document.querySelector("#email"); 
const name = document.querySelector("#name"); 
const surname = document.querySelector("#surname"); 
const telephone = document.querySelector("#cellphone");
const password = document.querySelector("#password"); 
const passwordConfirm = document.querySelector("#passwordConfirm"); 
const token = document.querySelector("#token"); 
const submit = document.querySelector("input[type='submit']");

email.oninput = checkEnableSubmitButton;
name.oninput = checkEnableSubmitButton;
surname.oninput = checkEnableSubmitButton;
telephone.oninput = checkEnableSubmitButton;
password.oninput = checkEnableSubmitButton;
passwordConfirm.oninput = checkEnableSubmitButton;
if(token != undefined){
    token.oninput = checkEnableSubmitButton;
}

function checkEnableSubmitButton(){
    //check that the input fields are not empty
    let valid = email.value != "";
    valid = valid && name.value != "";
    valid = valid && surname.value != "";
    valid = valid && password.value != "";
    valid = valid && password.value == passwordConfirm.value;
    if(token != undefined){
        valid = valid && token.value != "";
    }

    //then check that the values are actually correct
    valid = valid && email.checkValidity();
    valid = valid && telephone.checkValidity();
    valid = valid && password.value == passwordConfirm.value;

    console.log(valid)

    submit.disabled = !valid;
}

checkEnableSubmitButton();
