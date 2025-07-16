window.onload = () => {
    const inputQuantities = document.querySelectorAll('input[name="quantity[]"]');
    const variantIds = document.querySelectorAll("input[name='ids[]']");
    const rows = document.querySelectorAll("input[name='rows[]']");
    const costs = document.querySelectorAll("input[name='costs[]']");
    const total = document.querySelector("#total");
    const subtotals = document.querySelectorAll("h3[id^='subtotale']");

    for(let i = 0; i < inputQuantities.length; i++){
        inputQuantities[i].onchange = () => {
            const quantita = inputQuantities[i].value;
            const variantId = variantIds[i].value;
            const productId = rows[i].value;
            const cost = costs[i].value;
            const totalElm = subtotals[i];
            if(!isNaN(quantita) && !isNaN(variantId) && !isNaN(productId)){
                fetch("/api/updateCartQuantity.php?" + new URLSearchParams({
                    "id": productId, 
                    "variante": variantId, 
                    "quantita": quantita 
                }).toString()
                .then(res => res.json())
                .then(json => {
                    const totalCost = json["total"] / 100;
                    const newQuantity = json["newQuantity"];
                    var subtotal = (parseInt(newQuantity)*parseInt(cost) / 100);
                    totalElm.innerText = `Subtotale: €${subtotal}`;
                    total.innerText = `Totale: € ${totalCost}`;
                })
            }
        }
    }
}


