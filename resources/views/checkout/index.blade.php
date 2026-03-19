@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 pt-6 pb-12">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Checkout</h1>
        <p class="text-white/70 mt-1">Enter your details and we’ll send you confirmation.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-200 rounded-xl p-4">
            <div class="font-semibold mb-2">Please fix the following:</div>
            <ul class="list-disc pl-6 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-zinc-950/60 border border-white/10 rounded-2xl p-6">
            <form method="POST" action="{{ route('checkout.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm text-white/70 mb-1">Name</label>
                    <input name="name" value="{{ old('name') }}" required
                           class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 outline-none focus:border-white/25" />
                </div>

                <div>
                    <label class="block text-sm text-white/70 mb-1">Phone</label>
                    <input name="phone" value="{{ old('phone') }}" required
                           class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 outline-none focus:border-white/25" />
                </div>

                <div>
                    <label class="block text-sm text-white/70 mb-1">Address</label>
                    <textarea name="address" rows="4" required
                              class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 outline-none focus:border-white/25">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm text-white/70 mb-1">Email (optional)</label>
                    <input name="email" value="{{ old('email') }}" type="email"
                           class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 outline-none focus:border-white/25" />
                </div>

                <button type="submit"
                        class="w-full text-white py-3 rounded-xl transition font-semibold"
                        style="background: var(--accent);"
                        onmouseover="this.style.background='var(--accent-hover)';"
                        onmouseout="this.style.background='var(--accent)';">
                    Submit Order →
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-zinc-950/60 border border-white/10 rounded-2xl p-6">
            <div class="text-lg font-bold mb-4">Order Summary</div>
            <div class="space-y-3">
                @foreach($cart as $id => $item)
                    <div class="flex justify-between gap-4 text-sm">
                        <div class="min-w-0">
                            <div class="font-semibold truncate">{{ $item['name'] }}</div>
                            <div class="text-white/60">Qty: {{ $item['quantity'] }}</div>
                        </div>
                        <div class="text-right font-semibold" style="color: var(--accent);">
                        EGP{{ number_format($item['price'] * $item['quantity'], 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-white/10 mt-5 pt-4 flex justify-between font-bold">
                <span>Total</span>
                <span style="color: var(--accent);">EGP{{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

