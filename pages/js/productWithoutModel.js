//Variant selection
const variant = document.querySelectorAll("div.variantOption");
const addToCart = document.querySelector("input[name='idVariant']");
variant.forEach(v => {
    const radioButton = v.querySelector("input[type='radio']");
    v.onclick = () => {
        radioButton.click();
        //Set id for shopping cart
        if(addToCart){
            addToCard.value = radioButton.id;    
        }
    }
});
//Automatically select the first variant
variant[0].click();
