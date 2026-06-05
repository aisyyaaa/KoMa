<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerOrderController extends Controller
{
    public function index()
    {
        $sellerId = Auth::guard('seller')->id();

        $orders = Order::with(['items.product', 'user'])
            ->whereHas('items.product', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            })
            ->latest()
            ->paginate(15);

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $sellerId = Auth::guard('seller')->id();

        abort_unless(
            $order->items()->whereHas('product', fn($q) => $q->where('seller_id', $sellerId))->exists(),
            403
        );

        $order->load('items.product', 'user');
        return view('seller.orders.show', compact('order'));
    }

    public function create()
    {
        $sellerId = Auth::guard('seller')->id();
        $products = Product::where('seller_id', $sellerId)->where('is_active', true)->get();
        return view('seller.orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $sellerId = Auth::guard('seller')->id();

        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'payment_method' => 'required|in:COD,Transfer',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
        ]);

        // Pastikan semua produk milik seller ini
        $productIds = collect($request->items)->pluck('product_id');
        $validCount = Product::where('seller_id', $sellerId)->whereIn('id', $productIds)->count();
        abort_unless($validCount === $productIds->count(), 403);

        DB::transaction(function () use ($request) {
            $total = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal = $product->price * $item['qty'];
                $total += $subtotal;
                $itemsData[] = [
                    'product_id' => $product->id,
                    'qty'        => $item['qty'],
                    'price'      => $product->price,
                ];
            }

            $order = Order::create([
                'user_id'        => null,
                'name'           => $request->name,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'payment_method' => $request->payment_method,
                'status'         => 'Menunggu',
                'total'          => $total,
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }
        });

        return redirect()->route('seller.orders.index')->with('success', 'Pesanan manual berhasil dibuat!');
    }

    public function edit(Order $order)
    {
        $sellerId = Auth::guard('seller')->id();

        abort_unless(
            $order->items()->whereHas('product', fn($q) => $q->where('seller_id', $sellerId))->exists(),
            403
        );

        $products = Product::where('seller_id', $sellerId)->where('is_active', true)->get();
        $order->load('items.product');
        return view('seller.orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $sellerId = Auth::guard('seller')->id();

        abort_unless(
            $order->items()->whereHas('product', fn($q) => $q->where('seller_id', $sellerId))->exists(),
            403
        );

        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'payment_method' => 'required|in:COD,Transfer',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
        ]);

        $productIds = collect($request->items)->pluck('product_id');
        $validCount = Product::where('seller_id', $sellerId)->whereIn('id', $productIds)->count();
        abort_unless($validCount === $productIds->count(), 403);

        DB::transaction(function () use ($request, $order) {
            $total = 0;
            $order->items()->delete();

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal = $product->price * $item['qty'];
                $total += $subtotal;
                $order->items()->create([
                    'product_id' => $product->id,
                    'qty'        => $item['qty'],
                    'price'      => $product->price,
                ]);
            }

            $order->update([
                'name'           => $request->name,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'payment_method' => $request->payment_method,
                'total'          => $total,
            ]);
        });

        return redirect()->route('seller.orders.show', $order)->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $sellerId = Auth::guard('seller')->id();

        abort_unless(
            $order->items()->whereHas('product', fn($q) => $q->where('seller_id', $sellerId))->exists(),
            403
        );

        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Dikirim,Selesai',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('seller.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
