const addVariantButton = document.querySelector("#addVariant");
const variantContainer = document.querySelector("#variantContainer");
const selectBox = document.querySelector("#selectBox");
const submitButton = document.querySelector("input[type='submit']");
let defaultRadioButtons = document.querySelectorAll("input[type='radio']");

function defaultVariantPresent(){
    let alreadyPresent = false;
    for(let button of defaultRadioButtons){
        alreadyPresent = alreadyPresent || button.checked;
    }
    return alreadyPresent;
}

addVariantButton.onclick = () => {
    if(selectBox.selectedIndex >= 0){
        fetch(`/api/getMaterial.php?id=${selectBox.value}`)
            .then(res => res.json())
            .then(obj => {
                const hiddenDiv = document.createElement("div");
                hiddenDiv.setAttribute("class","variantInfo");
                variantContainer.appendChild(hiddenDiv);

                const hiddenId = document.createElement("input");
                hiddenId.setAttribute("type","hidden");
                hiddenId.setAttribute("name","materialIds[]");
                hiddenId.setAttribute("value",selectBox.value);
                hiddenDiv.appendChild(hiddenId);
                
                const svg = document.createElementNS("http://www.w3.org/2000/svg","svg");
                svg.setAttribute("width","40");
                svg.setAttribute("height","40px");
                const ellipse = document.createElementNS("http://www.w3.org/2000/svg","ellipse");
                ellipse.setAttribute("stroke","black");
                ellipse.setAttribute("fill","#"+obj["hexColore"]);
                ellipse.setAttribute("stroke-width","2");
                ellipse.setAttribute("rx","16");
                ellipse.setAttribute("ry","16");
                ellipse.setAttribute("cx","20");
                ellipse.setAttribute("cy","20");
                svg.appendChild(ellipse);
                hiddenDiv.appendChild(svg);

                const labelName = document.createElement("label");
                labelName.innerText = `${obj["nomeColore"]} (${obj["tipologia"]})`;
                hiddenDiv.appendChild(labelName);
                
                const variantCost = document.createElement("input");
                variantCost.setAttribute("type","number");
                variantCost.setAttribute("name","variantCosts[]");
                variantCost.setAttribute("value","00");
                hiddenDiv.appendChild(variantCost);

                const defaultButton = document.createElement("input");
                defaultButton.setAttribute("type", "radio");
                defaultButton.setAttribute("name", "defaultVariant");
                defaultButton.setAttribute("value", selectBox.value);
                defaultButton.setAttribute("id", selectBox.value);
                if(!defaultVariantPresent()){
                    defaultButton.setAttribute("checked", "checked");
                }
                hiddenDiv.appendChild(defaultButton);

                const labelDefault = document.createElement("label");
                labelDefault.setAttribute("for", selectBox.value);
                labelDefault.innerText = "Default";
                hiddenDiv.appendChild(labelDefault);

                const labelRemove = document.createElement("label");
                labelRemove.setAttribute("for", `removeVariant[${selectBox.value}]`);
                labelRemove.innerText = "Rimuovi"; 
                hiddenDiv.appendChild(labelRemove);

                const removeVariant = document.createElement("input");
                removeVariant.setAttribute("type","checkbox");
                removeVariant.setAttribute("name",`removeVariant[${selectBox.value}]`);
                removeVariant.setAttribute("id",`removeVariant[${selectBox.value}]`);
                removeVariant.setAttribute("value",selectBox.value);
                hiddenDiv.appendChild(removeVariant);

                selectBox.options.remove(selectBox.selectedIndex);
                console.log(selectBox.options);
                defaultRadioButtons = document.querySelectorAll("input[type='radio']");
            })
    }
}

submitButton.onclick = (ev) => {
    if(!defaultVariantPresent()){
        ev.preventDefault();
    }
}
