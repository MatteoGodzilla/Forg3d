let chosenColor = "";
let stlViewer;
//Create stl viewer
const showButton = document.querySelector("#showModel");
const container = document.querySelector("#model-viewer");

showButton.onclick = () => {
    container.classList.remove("hidden");
    stlViewer = new StlViewer(container, {
        auto_resize: false,
        models:[ { id: 0, filename: modelPath} ],
        allow_drag_and_drop: false,
        all_loaded_callback: () => {
            //Just to set the initial color
            stlViewer.set_color(0, chosenColor);
        }
    }); 
    showButton.style.display = "none";
}
//Variant selection
const variant = document.querySelectorAll("div.variantOption");
const addToCart = document.querySelector("input[name='idVariant']");
variant.forEach(v => {
    const radioButton = v.querySelector("input[type='radio']");
    const variantColor = v.querySelector("input[type='hidden']");
    v.onclick = () => {
        radioButton.click();
        //Set id for shopping cart
        if(addToCart)
            addToCart.value = radioButton.id;    
        
        chosenColor = variantColor.value;
        //Set model color
        if (stlViewer)
            stlViewer.set_color(0, chosenColor);
    }
});
//Automatically select the first variant
variant[0].click();
