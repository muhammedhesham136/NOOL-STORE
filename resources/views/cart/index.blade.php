@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 pt-6 pb-12">
    <h1 class="text-4xl font-bold mb-8">Your Shopping Cart 🛒</h1>

    @if(empty($cart) || count($cart) == 0)
        <div class="text-center py-16">
            <div class="text-8xl mb-4">🧶</div>
            <h2 class="text-2xl text-white/70 mb-4">Your cart is empty</h2>
            <a href="/" class="inline-block text-white px-8 py-3 rounded-lg transition"
               style="background: var(--accent);"
               onmouseover="this.style.background='var(--accent-hover)';"
               onmouseout="this.style.background='var(--accent)';">
                Start Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-zinc-950/60 border border-white/10 rounded-2xl shadow-sm overflow-hidden">
                    @foreach($cart as $id => $item)
                    <div class="cart-item p-6 border-b border-white/10 last:border-b-0" data-id="{{ $id }}" id="cart-item-{{ $id }}">
                        <div class="flex items-center gap-6">
                            <!-- Product Image -->
                            <div class="w-24 h-24 bg-zinc-900/60 rounded-lg flex items-center justify-center text-4xl border border-white/10">
                                @php
                                    $image = $item['image'] ?? null;
                                    $imageUrl = null;
                                    if ($image) {
                                        $imageUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
                                            ? $image
                                            : asset('storage/' . ltrim($image, '/'));
                                    }
                                @endphp
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    🧶
                                @endif
                            </div>
                            
                            <!-- Product Details -->
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold">{{ $item['name'] }}</h3>
                                <p class="font-bold mt-1" style="color: var(--accent);">EGP{{ number_format($item['price'], 2) }}</p>
                            </div>
                            
                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-4">
                                <div class="flex items-center border border-white/10 rounded-lg bg-black/20">
                                    <button class="decrease-qty px-4 py-2 hover:bg-white/5 rounded-l-lg text-xl font-bold" 
                                            data-id="{{ $id }}">-</button>
                                    <span id="qty-{{ $id }}" class="qty-display px-6 py-2 border-x border-white/10 min-w-[60px] text-center font-medium">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <button class="increase-qty px-4 py-2 hover:bg-white/5 rounded-r-lg text-xl font-bold" 
                                            data-id="{{ $id }}">+</button>
                                </div>
                                
                                <!-- Item Subtotal -->
                                <div class="text-right min-w-[100px]">
                                    <div class="text-sm text-white/60">Subtotal</div>
                                    <span id="subtotal-{{ $id }}" class="item-subtotal text-lg font-bold" style="color: var(--accent);">
                                    EGP{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </span>
                                </div>
                                
                                <!-- Remove Button -->
                                <button class="remove-item text-red-500 hover:text-red-700 p-2" data-id="{{ $id }}">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-zinc-950/60 border border-white/10 rounded-2xl shadow-sm p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-white/70">Subtotal</span>
                            <span id="cart-subtotal" class="font-semibold">EGP{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/70">Shipping</span>
                            <span class="font-semibold">Free</span>
                        </div>
                        <div class="border-t border-white/10 pt-3 mt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span id="cart-total" style="color: var(--accent);">EGP{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}"
                       class="block w-full text-center text-white py-3 rounded-lg transition font-medium"
                       style="background: var(--accent);"
                       onmouseover="this.style.background='var(--accent-hover)';"
                       onmouseout="this.style.background='var(--accent)';">
                        Proceed to Checkout →
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.cart-item {
    transition: background-color 0.2s;
}
.cart-item:hover {
    background-color: rgba(255, 255, 255, 0.03);
}
.loading {
    opacity: 0.5;
    pointer-events: none;
    position: relative;
}
.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid var(--accent);
    border-top-color: transparent;
    border-radius: 50%;
    animation: spinner 0.6s linear infinite;
}
@keyframes spinner {
    to { transform: rotate(360deg); }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart page initialized');
    
    // Set up event listeners
    setupEventListeners();
});

function setupEventListeners() {
    // Decrease buttons
    document.querySelectorAll('.decrease-qty').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id;
            updateQuantity(productId, 'decrease');
        });
    });
    
    // Increase buttons
    document.querySelectorAll('.increase-qty').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id;
            updateQuantity(productId, 'increase');
        });
    });
    
    // Remove buttons
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id;
            removeFromCart(productId);
        });
    });
}

function updateQuantity(productId, action) {
    const qtySpan = document.getElementById(`qty-${productId}`);
    if (!qtySpan) return;
    
    let currentQty = parseInt(qtySpan.textContent);
    let newQty = currentQty;
    
    if (action === 'increase') {
        newQty = currentQty + 1;
    } else if (action === 'decrease' && currentQty > 1) {
        newQty = currentQty - 1;
    } else {
        return; // Don't go below 1
    }
    
    // Show loading on this item
    const cartItem = document.getElementById(`cart-item-${productId}`);
    if (cartItem) cartItem.classList.add('loading');
    
    // Send update to server
    fetch(`/cart/update/${productId}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ quantity: newQty })
    })
    .then(async (response) => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(data.message || `Request failed (${response.status})`);
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            // Update quantity display
            qtySpan.textContent = data.quantity ?? newQty;
            
            // Update item subtotal
            const subtotalSpan = document.getElementById(`subtotal-${productId}`);
            if (subtotalSpan && data.itemTotal) {
                subtotalSpan.textContent = `$${data.itemTotal.toFixed(2)}`;
            }
            
            // Update cart total
            if (data.total) {
                updateCartTotal(data.total);
            }
            
            // Update header cart count
            if (data.count !== undefined) {
                updateHeaderCount(data.count);
            } else {
                fetchCartCount();
            }
            
            showNotification('Cart updated ✓');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating cart', 'error');
    })
    .finally(() => {
        if (cartItem) cartItem.classList.remove('loading');
    });
}

function removeFromCart(productId) {
    if (!confirm('Remove this item from your cart?')) return;
    
    const cartItem = document.getElementById(`cart-item-${productId}`);
    if (cartItem) cartItem.classList.add('loading');
    
    fetch(`/cart/remove/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(async (response) => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(data.message || `Request failed (${response.status})`);
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            if (cartItem) cartItem.remove();

            if (data.total) {
                updateCartTotal(data.total);
            }

            if (data.count !== undefined) {
                updateHeaderCount(data.count);
            } else {
                fetchCartCount();
            }

            showNotification('Item removed ✓');

            if (document.querySelectorAll('.cart-item').length === 0) {
                setTimeout(() => location.reload(), 500);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error removing item', 'error');
    })
    .finally(() => {
        if (cartItem) cartItem.classList.remove('loading');
    });
}

function updateCartTotal(total) {
    const formattedTotal = `$${parseFloat(total).toFixed(2)}`;
    
    const subtotalEl = document.getElementById('cart-subtotal');
    const totalEl = document.getElementById('cart-total');
    
    if (subtotalEl) subtotalEl.textContent = formattedTotal;
    if (totalEl) totalEl.textContent = formattedTotal;
}

function fetchCartCount() {
    fetch('/cart/count', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.count !== undefined) {
            updateHeaderCount(data.count);
        }
    })
    .catch(error => console.error('Error fetching count:', error));
}

function updateHeaderCount(count) {
    const cartBadge = document.getElementById('cartCount');
    if (cartBadge) {
        if (count > 0) {
            cartBadge.textContent = count;
            cartBadge.classList.remove('hidden');
        } else {
            cartBadge.classList.add('hidden');
        }
        localStorage.setItem('cartTotal', count);
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 text-white font-medium animate-slide-in';
    notification.style.backgroundColor = type === 'success' ? 'var(--accent)' : '#ef4444';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 2000);
}

// Add animation style
const style = document.createElement('style');
style.textContent = `
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
    .animate-slide-in {
        animation: slideIn 0.3s ease;
    }
`;
document.head.appendChild(style);
</script>
@endpush