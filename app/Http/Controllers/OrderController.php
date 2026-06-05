<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Halaman pesanan buyer
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    // Simpan order baru (payment)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $cart = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // Buat order
        $total = $cart->sum(fn($i) => (float)$i->product->price * (int)$i->qty);

        $order = Order::create([
            'user_id' => Auth::id(),
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'payment_method' => 'COD',
            'status'  => 'Menunggu',
            'total'   => $total,   
        ]);


        // Buat order_items dan kurangi stok produk
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'price' => $item->product->price,
            ]);

            // Kurangi stok
            $item->product->decrement('stock', $item->qty);
        }

        // Kosongkan cart user
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat. Menunggu konfirmasi penjual.');
    }
}
