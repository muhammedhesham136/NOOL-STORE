<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = $this->calculateTotal();
        return view('cart.index', compact('cart', 'total'));
    }

        public function add(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $cart = session()->get('cart', []);
            
            if(isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                    'image' => $product->image
                ];
            }
            
            session()->put('cart', $cart);
            
            if($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'count' => array_sum(array_column($cart, 'quantity')),
                    'total' => $this->calculateTotal()
                ]);
            }
            
            return redirect()->back()->with('success', 'Product added to cart!');
            
        } catch (\Exception $e) {
            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error adding to cart: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.',
            ], 404);
        }

        $quantity = (int) $request->input('quantity', $cart[$id]['quantity']);
        if ($quantity < 1) {
            $quantity = 1;
        }

        $cart[$id]['quantity'] = $quantity;
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'total' => $this->calculateTotal(),
            'count' => array_sum(array_column($cart, 'quantity')),
            'itemTotal' => $cart[$id]['price'] * $cart[$id]['quantity'],
            'quantity' => $cart[$id]['quantity'],
        ]);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'total' => $this->calculateTotal(),
            'count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return response()->json([
            'count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    private function calculateTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}