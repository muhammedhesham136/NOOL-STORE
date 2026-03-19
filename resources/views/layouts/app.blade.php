<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nool')</title>
    <link rel="icon" type="image/svg+xml" href="/nool-logo.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --accent: #800020;
            --accent-hover: #5a0016;
            --bg-1: #3a0012;
            --bg-2: #140007;
            --bg-3: #000000;
        }
        @keyframes fadeIn { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes float { 0% { transform:translateY(0px); } 50% { transform:translateY(-10px); } 100% { transform:translateY(0px); } }
        .fade-in { animation: fadeIn 0.8s ease-out; }
        .float { animation: float 3s ease-in-out infinite; }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .btn-primary { background-color: var(--accent); color: white; padding: 0.75rem 1.5rem; border-radius: 9999px; font-weight: 600; transition: all 0.3s ease; display: inline-block; cursor: pointer; }
        .btn-primary:hover { background-color: var(--accent-hover); transform: scale(1.05); }
        .cart-badge { position: absolute; top: -8px; right: -8px; background-color: var(--accent); color: white; border-radius: 9999px; width: 20px; height: 20px; font-size: 12px; display: flex; align-items: center; justify-content: center; }
        body.site-bg {
            background:
                radial-gradient(900px 600px at 15% 10%, rgba(128, 0, 32, 0.55), rgba(0, 0, 0, 0) 60%),
                radial-gradient(900px 600px at 85% 0%, rgba(128, 0, 32, 0.35), rgba(0, 0, 0, 0) 60%),
                linear-gradient(180deg, var(--bg-1) 0%, var(--bg-2) 45%, var(--bg-3) 100%);
            background-attachment: fixed;
        }
    </style>
</head>
<body class="site-bg text-white">
    <nav class="bg-black/95 border-b border-white/10 backdrop-blur fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3">
                <img src="/nool-logo.svg" alt="Nool" class="w-10 h-10 rounded-full ring-1 ring-white/10">
                <div class="leading-tight">
                    <div class="text-2xl font-extrabold tracking-tight" style="color: var(--accent);">Nool</div>
                    <div class="text-xs text-white/60 -mt-0.5">for handmade lovers</div>
                </div>
            </a>
            <div class="relative">
                <a href="/cart" class="text-white/90 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span id="cartCount" class="cart-badge hidden">0</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="min-h-screen pt-24">
        @yield('content')
    </main>

    <footer class="bg-black border-t border-white/10 mt-0 py-8 text-center text-white/60">
        <p>© 2024 Nool. Handmade with love.</p>
    </footer>

    <script>
        function updateCartCount() {
            fetch('/cart/count')
                .then(r => r.json())
                .then(d => {
                    const el = document.getElementById('cartCount');
                    if(d.count > 0) { 
                        el.textContent = d.count; 
                        el.classList.remove('hidden'); 
                    } else { 
                        el.classList.add('hidden'); 
                    }
                })
                .catch(e => console.error('Cart count error:', e));
        }
        
        function showNotification(msg) {
            const n = document.createElement('div');
            n.className = 'fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            n.style.backgroundColor = 'var(--accent)';
            n.textContent = msg;
            document.body.appendChild(n);
            setTimeout(() => n.remove(), 2000);
        }
        
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
    @stack('scripts')
</body>
</html>
