//Review stuff
const toggleButton = document.querySelector("#toggleReviewForm");
const form = document.querySelector("form.hidden");
const slider = document.querySelector("input[type='range']");
const scoreDisplay = document.querySelector("label[for='score'] span");
console.log(scoreDisplay);
slider.oninput = (ev) => scoreDisplay.innerText = ev.srcElement.value;

toggleButton.onclick = () => {
    if(form.classList.contains("hidden")){
        form.classList.remove("hidden");
    } else {
        form.classList.add("hidden");
    }
}
