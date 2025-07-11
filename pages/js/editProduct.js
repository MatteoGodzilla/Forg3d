const addVariantButton = document.querySelector("#addVariant");
const variantContainer = document.querySelector("#variantContainer");
const selectBox = document.querySelector("#selectBox");
const submitButton = document.querySelector("input[type='submit']");
const centsInputs = document.querySelectorAll("input[name^='variantCostsCents']");
let defaultRadioButtons = document.querySelectorAll("input[type='radio']");

function defaultVariantPresent(){
    let alreadyPresent = false;
    for(let button of defaultRadioButtons){
        alreadyPresent = alreadyPresent || button.checked;
    }
    return alreadyPresent;
}

console.log(centsInputs);
for(const input of centsInputs){
    input.onchange = adjustCentsValue;
}

addVariantButton.onclick = () => {
    if(selectBox.selectedIndex >= 0){
        fetch(`/api/getMaterial.php?id=${selectBox.value}`)
            .then(res => res.json())
            .then(obj => {
                const rootDiv = document.createElement("div");
                rootDiv.classList.add("variantInfo");

                //id 
                const hiddenId = document.createElement("input");
                    hiddenId.setAttribute("type","hidden");
                    hiddenId.setAttribute("name",`materialIds[${selectBox.value}]`);
                    hiddenId.setAttribute("value",selectBox.value);
                rootDiv.appendChild(hiddenId);

                //svg
                const innerDiv = document.createElement("div");
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
                    const labelName = document.createElement("label");
                        labelName.innerText = `${obj["nomeColore"]} (${obj["tipologia"]})`;

                    innerDiv.appendChild(svg);
                    innerDiv.appendChild(labelName);
                rootDiv.appendChild(innerDiv);

                //Variant cost
                const innerDiv2 = document.createElement("div");
                    const labelName2 = document.createElement("label");
                        labelName2.setAttribute("for", `variantCostsWhole[${selectBox.value}]`);
                        labelName2.innerText = "Centesimi:";

                    const variantCost = document.createElement("input");
                        variantCost.setAttribute("type","number");
                        variantCost.setAttribute("name",`variantCostsWhole[${selectBox.value}]`);
                        variantCost.setAttribute("id",`variantCostsWhole[${selectBox.value}]`);
                        variantCost.setAttribute("value","0");

		    const decimalSeparator = document.createElement("p");
		    	decimalSeparator.innerText = ",";

		    const variantCostCents = document.createElement("input");
			variantCostCents.setAttribute("type", "number");
			variantCostCents.setAttribute("name", `variantCostsCents[${selectBox.value}]`);
			variantCostCents.setAttribute("id", `variantCostsCents[${selectBox.value}]`);
			variantCostCents.setAttribute("value", "00");
			variantCostCents.onchange = adjustCentsValue;

                    innerDiv2.appendChild(labelName2);
                    innerDiv2.appendChild(variantCost);
		    innerDiv2.appendChild(decimalSeparator);
		    innerDiv2.appendChild(variantCostCents);
                rootDiv.appendChild(innerDiv2);

                //Bottom controls
                const innerDiv3 = document.createElement("div");
                    const innerDiv3_1 = document.createElement("div");
                        const defaultButton = document.createElement("input");
                            defaultButton.setAttribute("type", "radio");
                            defaultButton.setAttribute("name", "defaultVariant");
                            defaultButton.setAttribute("id", selectBox.value);
                            defaultButton.setAttribute("value", selectBox.value);
                            if(!defaultVariantPresent()){
                                defaultButton.setAttribute("checked", "checked");
                            }
                        const labelDefault = document.createElement("label");
                            labelDefault.setAttribute("for", selectBox.value);
                            labelDefault.innerText = "Default";

                        innerDiv3_1.appendChild(defaultButton);
                        innerDiv3_1.appendChild(labelDefault);
                        
                    const innerDiv3_2 = document.createElement("div");
                        const removeVariant = document.createElement("input");
                            removeVariant.setAttribute("type","checkbox");
                            removeVariant.setAttribute("name",`removeVariant[${selectBox.value}]`);
                            removeVariant.setAttribute("id",`removeVariant[${selectBox.value}]`);
                            removeVariant.setAttribute("value",selectBox.value);
                        const labelRemove = document.createElement("label");
                            labelRemove.setAttribute("for", `removeVariant[${selectBox.value}]`);
                            labelRemove.innerText = "Rimuovi"; 

                        innerDiv3_2.appendChild(removeVariant);
                        innerDiv3_2.appendChild(labelRemove);

                    innerDiv3.appendChild(innerDiv3_1);
                    innerDiv3.appendChild(innerDiv3_2);

                rootDiv.appendChild(innerDiv3);

                variantContainer.appendChild(rootDiv);
                // Update possible materials
                selectBox.options.remove(selectBox.index);
                defaultRadioButtons = document.querySelectorAll("input[type='radio']");
            })
    }
}

submitButton.onclick = (ev) => {
    if(!defaultVariantPresent()){
        ev.preventDefault();
    }
}


function adjustCentsValue(evt){
    const elm = evt.srcElement;
    elm.value =(elm.value.substring(0, 2)).padStart(2, "0");
}
