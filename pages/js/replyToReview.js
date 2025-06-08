const buttons = document.querySelectorAll("button.showReplyForm");
const forms = document.querySelectorAll("form.reply");

for(let i = 0; i < buttons.length; i++) {
    const button = buttons[i];
    const form = forms[i];

    button.onclick = () => {
        if(form.classList.contains("hidden")){
            form.classList.remove("hidden"); 
            button.innerText = "Chiudi";
        } else {
            form.classList.add("hidden"); 
            button.innerText = "Rispondi";
        }
    }
}
