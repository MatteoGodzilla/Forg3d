$(document).ready(function() {
    $('input[name^="quantity["]').on('change', function() {
         var quantita = $(this).val();
         var index = ($('input[name^="quantity["]').index(this));
         var variantId =$('input[name^="ids["]')[index].value;
         var productId =$('input[name^="rows["]')[index].value;
        if(!isNaN(quantita) && !isNaN(variantId) && !isNaN(productId)){
            fetch("/api/updateCartQuantity.php?id="+productId+"&variante="+variantId+"&quantita="+quantita);
            var cost = $('input[name^="costs["]')[index].value;
            var subtotal = (parseInt(quantita)*parseInt(cost));
            $('h3[name^="total["]').eq(index).text("Subtotale: "+subtotal+"$");
        }

    });
});