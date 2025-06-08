const toggleButton = document.querySelector("toggleReportForm");
const form = document.querySelector("form.hidden2");

toggleButton.onclick = () => {
    if(form.classList.contains("hidden2")){
        form.classList.remove("hidden2");
    } else {
        form.classList.add("hidden2");
    }
}