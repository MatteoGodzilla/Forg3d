//Review stuff
const toggleButton = document.querySelector("#toggleReviewForm");
const form = document.querySelector("form.hidden");
const slider = document.querySelector("input[type='range']");
const scoreDisplay = document.querySelector("label[for='score'] span");
const scoreStars = document.querySelectorAll("div.star-preview > span");
slider.oninput = updateRating;

toggleButton.onclick = () => {
    if(form.classList.contains("hidden")){
        form.classList.remove("hidden");
    } else {
        form.classList.add("hidden");
    }
}

function updateRating(ev){
    scoreDisplay.innerText = ev.srcElement.value;
    for(const star of scoreStars){
        star.classList.remove("filled");
    }
    for(let i = 0; i < Number(ev.srcElement.value); i++){
        scoreStars[i+1].classList.add("filled");
    }
}
