function updateQuantity(cartId, action) {
    const input = document.getElementById(`qty-${cartId}`);
    let value = parseInt(input.value);
    
    if (action === 'increase' && value < 10) {
        value++;
    } else if (action === 'decrease' && value > 1) {
        value--;
    } else if (action === 'input') {
        value = Math.min(Math.max(value, 1), 10);
    }
    
    input.value = value;
    
    fetch('?act=update-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cart_id: cartId,
            quantity: value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Có lỗi xảy ra khi cập nhật số lượng');
        }
    });
}

function removeItem(cartId) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        fetch('?act=remove-cart-item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cart_id: cartId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi xóa sản phẩm');
            }
        });
    }
} 