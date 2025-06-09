window.onload = () => {
    const buttons = document.querySelectorAll("button");
    const sections = document.querySelectorAll("section");

    for(let i = 0; i < buttons.length; i++){
        buttons[i].onclick = () => {
            const section = sections[i];
            section.classList.toggle("hidden");
        }
    }
}
