@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 pt-10 pb-16 text-center">
    <div class="text-6xl mb-4">✅</div>
    <h1 class="text-3xl font-extrabold mb-2">Order received</h1>
    <p class="text-white/70 mb-8">
        We’ll contact you as soon as possible.
    </p>

    <a href="{{ route('home') }}"
       class="inline-block text-white px-8 py-3 rounded-xl transition font-semibold"
       style="background: var(--accent);"
       onmouseover="this.style.background='var(--accent-hover)';"
       onmouseout="this.style.background='var(--accent)';">
        Continue shopping →
    </a>
</div>
@endsection

