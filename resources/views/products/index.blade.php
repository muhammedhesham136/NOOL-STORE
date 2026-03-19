@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 pt-16 pb-12">
    <div class="text-center mb-10 fade-in">
        <div class="mb-6">
            <div class="text-5xl sm:text-6xl font-black tracking-[0.35em]"
                 style="color:#D4AF37; text-shadow: 0 12px 30px rgba(0,0,0,0.55);">
                NOOL
            </div>
        </div>
        <h1 class="text-5xl font-bold mb-4">Handmade <span class="font-extrabold" style="color: var(--accent);">Crochet</span></h1>
        <p class="text-xl text-white/70">Each piece crafted with love, just for you</p>
        <div class="float text-7xl mt-4">🧶</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($products as $product)
        <div class="product-card bg-zinc-950/60 border border-white/10 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
            <div class="h-64 bg-zinc-900/60 relative group">
                @php
                    $image = $product->image;
                    $imageUrl = null;
                    if ($image) {
                        $imageUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
                            ? $image
                            : asset('storage/' . ltrim($image, '/'));
                    }
                @endphp
                @if($imageUrl)
                    <img src="{{ $imageUrl }}" 
                         class="w-full h-full object-cover"
                         onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-6xl\'>🧶</div>';">
                @else
                    <div class="w-full h-full flex items-center justify-center text-6xl">🧶</div>
                @endif
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-semibold">{{ $product->name }}</h3>
                    <span class="text-sm px-3 py-1 rounded-full border border-white/10" style="color: var(--accent); background: rgba(128, 0, 32, 0.12);">
                        {{ $product->category }}
                    </span>
                </div>
                <p class="text-white/70 mb-4 line-clamp-2">{{ $product->description }}</p>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-2xl font-bold" style="color: var(--accent);">EGP{{ number_format($product->price, 2) }}</span>
                    <span class="text-sm text-white/70 bg-white/5 border border-white/10 px-3 py-1 rounded-full">✓ {{ $product->stock }} in stock</span>
                </div>
                
                <!-- Quantity controls and Add to Cart -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center border border-white/10 rounded-lg bg-black/20">
                        <button onclick="updateQuantity({{ $product->id }}, 'decrease')" 
                            class="px-3 py-2 hover:bg-white/5 rounded-l-lg">-</button>
                        <span id="qty-{{ $product->id }}" class="px-4 py-2 border-x border-white/10 min-w-[50px] text-center">1</span>
                        <button onclick="updateQuantity({{ $product->id }}, 'increase')" 
                            class="px-3 py-2 hover:bg-white/5 rounded-r-lg">+</button>
                    </div>
                    <button onclick="addToCart({{ $product->id }})" 
                        class="flex-1 text-white py-2 rounded-lg transition flex items-center justify-center gap-2 font-medium"
                        style="background: var(--accent);"
                        onmouseover="this.style.background='var(--accent-hover)';"
                        onmouseout="this.style.background='var(--accent)';">
                        <span>Add</span>
                        <span>🛒</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add notification styles -->
<style>
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #8b9a7a;
    color: white;
    padding: 12px 24px;
    border-radius: 9999px;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    z-index: 9999;
    animation: slideIn 0.3s ease, fadeOut 0.3s ease 2.7s forwards;
    font-weight: 500;
}

.notification.error {
    background: #ef4444;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

@push('scripts')
<script>
// Store quantities for each product
let quantities = {};
let cartItems = {};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load saved cart data from localStorage
    const savedTotal = localStorage.getItem('cartTotal');
    const savedItems = localStorage.getItem('cartItems');
    
    if (savedTotal) {
        updateCartCount(parseInt(savedTotal));
    }
    
    if (savedItems) {
        try {
            cartItems = JSON.parse(savedItems);
        } catch(e) {
            cartItems = {};
        }
    }
    
    // Initialize quantities for all products
    document.querySelectorAll('[id^="qty-"]').forEach(el => {
        const id = el.id.replace('qty-', '');
        quantities[id] = 1;
    });
    
    // Fetch current cart count from server to ensure sync
    fetchCartCount();
});

function updateQuantity(productId, action) {
    const qtySpan = document.getElementById(`qty-${productId}`);
    if (!qtySpan) return;
    
    let currentQty = parseInt(qtySpan.textContent) || 1;
    
    if (action === 'increase') {
        currentQty++;
    } else if (action === 'decrease' && currentQty > 1) {
        currentQty--;
    }
    
    qtySpan.textContent = currentQty;
    quantities[productId] = currentQty;
}

function addToCart(productId) {
    const quantity = quantities[productId] || 1;
    const button = event.currentTarget;
    
    // Show loading state
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Adding...';

    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Update cart items
        if (!cartItems[productId]) {
            cartItems[productId] = 0;
        }
        cartItems[productId] += quantity;
        
        // Calculate total items
        const totalItems = Object.values(cartItems).reduce((sum, qty) => sum + qty, 0);
        
        // Update cart count
        updateCartCount(totalItems);
        
        // Reset quantity to 1
        document.getElementById(`qty-${productId}`).textContent = '1';
        quantities[productId] = 1;
        
        // Show success message
        showNotification(`✨ Added ${quantity} item${quantity > 1 ? 's' : ''} to cart!`);
    })
    .catch(error => {
        console.log('Using local cart update');
        
        // Even if server fails, update local cart
        if (!cartItems[productId]) {
            cartItems[productId] = 0;
        }
        cartItems[productId] += quantity;
        
        const totalItems = Object.values(cartItems).reduce((sum, qty) => sum + qty, 0);
        updateCartCount(totalItems);
        
        document.getElementById(`qty-${productId}`).textContent = '1';
        quantities[productId] = 1;
        
        showNotification(`✨ Added ${quantity} item${quantity > 1 ? 's' : ''} to cart!`);
    })
    .finally(() => {
        // Reset button
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function updateCartCount(total) {
    const cartBadge = document.getElementById('cart-count');
    if (cartBadge) {
        cartBadge.textContent = total;
        cartBadge.classList.remove('hidden');
        
        // Store in localStorage for persistence
        localStorage.setItem('cartTotal', total);
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
    }
}

function showNotification(message, type = 'success') {
    // Remove any existing notification
    const existingNotif = document.querySelector('.notification');
    if(existingNotif) {
        existingNotif.remove();
    }
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `notification ${type === 'error' ? 'error' : ''}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        if(notification && notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

function fetchCartCount() {
    // Try to get from server
    fetch('/cart/count', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.count !== undefined) {
            updateCartCount(data.count);
            if (data.items) {
                cartItems = data.items;
            }
        }
    })
    .catch(() => {
        // If server fails, use localStorage
        const savedTotal = localStorage.getItem('cartTotal');
        if (savedTotal) {
            updateCartCount(parseInt(savedTotal));
        }
    });
}

// Expose functions globally
window.addToCart = addToCart;
window.updateQuantity = updateQuantity;
</script>
@endpush