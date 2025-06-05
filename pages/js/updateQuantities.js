$(document).ready(function() {
    $('input[name^="quantity["]').on('change', function() {
         var quantita = $(this).val();
         var index = ($('input[name^="quantity["]').index(this));
         var variantId =$('input[name^="ids["]')[index].value;
         var productId =$('input[name^="rows["]')[index].value;
        if(!isNaN(quantita) && !isNaN(variantId) && !isNaN(productId)){
            var orderId = 1;
            fetch("/api/updateCartQuantity.php?id="+productId+"&variante="+variantId+"&quantita="+quantita);
        }

    });
});