const password_new = document.querySelector("#password_new"); 
const passwordConfirm = document.querySelector("#repeat"); 
const submit = document.querySelectorAll("input[type='submit']")[1];


password_new.oninput = checkEnableSubmitButton;
passwordConfirm.oninput = checkEnableSubmitButton;

function checkEnableSubmitButton(){
    //check that the input fields are not empty
    let valid = password_new.value != "";
    valid = valid && password_new.value == passwordConfirm.value;

    console.log(valid);

    submit.disabled = !valid;
}

checkEnableSubmitButton();
