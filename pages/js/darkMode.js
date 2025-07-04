const body = document.querySelector("body"); 
const themeToggle = document.getElementById("theme-toggle");

const KEY = "theme";
const DARK_VALUE = "dark";
const LIGHT_VALUE = "light";

//taken from google material icons
const spanIconToLightMode = "bedtime"
const spanIconToDarkMode = "clear_day"

function setTheme(toDark){
    if(toDark){
        body.classList.add("dark"); 
        localStorage.setItem(KEY, DARK_VALUE);
        themeToggle.innerText = spanIconToDarkMode;
    } else {
        body.classList.remove("dark"); 
        localStorage.setItem(KEY, LIGHT_VALUE);
        themeToggle.innerText = spanIconToLightMode;
    } 
}

themeToggle.addEventListener("click", () => {
    setTheme(!body.classList.contains("dark"));
});

function initialLoad(){
    if(localStorage.getItem(KEY) == null){
        //default for the first time
	const prefersDarkTheme = window.matchMedia("(prefers-color-scheme: dark)");
        setTheme(prefersDarkTheme);
    } else {
        //from second time onwards
        setTheme(localStorage.getItem(KEY) === DARK_VALUE);
    }
}

initialLoad()
