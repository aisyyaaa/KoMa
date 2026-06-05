<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        // ambil semua order beserta user yang pesan dan item-itemnya
        $orders = Order::with('items', 'user')->latest()->paginate(10);
    
        // hitung penjualan bulan ini
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->startOfDay();
        $endOfMonth   = $now->copy()->endOfMonth()->endOfDay();
    
        // jumlah pesanan selesai bulan ini
        $salesCount = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->where('status', 'Selesai')
                        ->count();
    
        // total omzet bulan ini
        $salesTotal = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->where('status', 'Selesai')
                        ->sum('total');

        // total seluruh order
        $totalOrders = \App\Models\Order::count();

        // lempar ke view admin/orders/index.blade.php
        return view('admin.orders.index', compact('orders', 'salesCount', 'salesTotal', 'totalOrders'));
    }
    


    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Dikirim,Selesai',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
