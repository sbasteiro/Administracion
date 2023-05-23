function shippingDelete(shippingId) {
    if (confirm('¿Estás seguro de borrar el envío?')) {
        $.ajax({
            url: "/api/shipping/"+ shippingId,
            type: "DELETE",
            context: document.body
        }).done(function() {
            alert('Envío eliminado');
            window.location.reload(true);
        });
    } else {
        alert('Eliminación cancelada');
    }
}

function shippingUpdate(shippingId) {
        $.ajax({
            url: "/api/shipping/"+ shippingId,
            type: "PATCH",
            context: document.body
        }).done(function() {
            window.location.reload(true);
        });
}
