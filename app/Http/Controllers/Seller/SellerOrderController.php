<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SellerOrderController extends Controller
{
    /**
     * Tampilkan daftar pesanan untuk penjual.
     * Sementara menggunakan data dummy sampai tabel order siap.
     */
    public function index()
    {
        $orders = $this->dummyOrders();

        return view('seller.orders.index', [
            'orders' => $orders,
            'orderStats' => [
                'pending' => $orders->where('status', 'pending')->count(),
                'processed' => $orders->where('status', 'processed')->count(),
                'shipped' => $orders->where('status', 'shipped')->count(),
                'completed' => $orders->where('status', 'completed')->count(),
            ],
        ]);
    }

    public function update(Request $request, string $orderId)
    {
        $status = $request->validate([
            'status' => 'required|in:pending,processed,shipped,completed',
        ])['status'];

        session()->put("dummy_orders.$orderId.status", $status);

        return redirect()->route('seller.orders.index')->with('success', "Status pesanan $orderId diperbarui.");
    }

    private function dummyOrders(): Collection
    {
        $defaults = collect([
            [
                'order_number' => 'INV-2025-001',
                'customer_name' => 'Alya Pratama',
                'product' => 'Buku Catatan Premium KoMa',
                'total' => 150000,
                'status' => 'pending',
                'created_at' => now()->subDays(1),
            ],
            [
                'order_number' => 'INV-2025-002',
                'customer_name' => 'Rizky Saputra',
                'product' => 'Modul Praktikum Algoritma',
                'total' => 85000,
                'status' => 'processed',
                'created_at' => now()->subDays(2),
            ],
            [
                'order_number' => 'INV-2025-003',
                'customer_name' => 'Salsa Putri',
                'product' => 'Buku Catatan Premium KoMa',
                'total' => 50000,
                'status' => 'shipped',
                'created_at' => now()->subDays(3),
            ],
            [
                'order_number' => 'INV-2025-004',
                'customer_name' => 'Dimas Arya',
                'product' => 'Modul Praktikum Algoritma',
                'total' => 85000,
                'status' => 'completed',
                'created_at' => now()->subDays(5),
            ],
        ]);

        $sessionOrders = collect(session()->get('dummy_orders', []));

        if ($sessionOrders->isEmpty()) {
            session()->put('dummy_orders', $defaults->keyBy('order_number')->toArray());
            return $defaults;
        }

        return $sessionOrders->map(function ($order) {
            $order['created_at'] = isset($order['created_at'])
                ? now()->parse($order['created_at'])
                : now();
            return $order;
        });
    }
}
