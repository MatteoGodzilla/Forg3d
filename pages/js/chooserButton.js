const toggleButton = document.querySelector("#typeSwitcher");
const submitButton = document.querySelector("input[type='submit']");
const formType = document.querySelector("input[name='type']");
let type = 0;

toggleButton.onclick = (evt) => {
    if(type == 0){
        type = 1;
        formType.value = type;
        toggleButton.classList.add("seller");
        toggleButton.classList.remove("buyer");
        submitButton.classList.add("seller");
        submitButton.classList.remove("buyer");
    } else {
        type = 0;
        formType.value = type;
        toggleButton.classList.remove("seller");
        toggleButton.classList.add("buyer");
        submitButton.classList.remove("seller");
        submitButton.classList.add("buyer");
    }
    evt.preventDefault();
}
toggleButton.click();
