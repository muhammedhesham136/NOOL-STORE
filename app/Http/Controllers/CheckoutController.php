<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $total = $this->calculateTotal($cart);

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $customer = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:254'],
        ]);

        $total = $this->calculateTotal($cart);

        $to = env('ORDER_TO_ADDRESS') ?: config('mail.from.address');

        Mail::to($to)->send(new NewOrderMail(
            customer: $customer,
            cart: $cart,
            total: $total
        ));

        if (!empty($customer['email'])) {
            Mail::to($customer['email'])->send(new NewOrderMail(
                customer: $customer,
                cart: $cart,
                total: $total,
                isCustomerCopy: true
            ));
        }

        session()->forget('cart');

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('checkout.success');
    }

    private function calculateTotal(array $cart): float
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0));
        }
        return $total;
    }
}

