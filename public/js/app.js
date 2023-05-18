function shippingDelete(shippingId) {
    const url = '/api/softDeleteShipping/' + shippingId;
    if (confirm('¿Estás seguro de borrar el envío?')) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
            })
        alert('Envío eliminado');
        window.location.reload(true);
    } else {
        alert('Eliminación cancelada');
    }
}

function shippingUpdate(shippingId) {
    const url = '/api/shippingUpdate/' + shippingId;
    fetch(url, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log(data.message);
        })
    alert('Envío eliminado');
    window.location.reload(true);
}
