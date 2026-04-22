<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Client;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PosController extends Controller
{
    public function index()
    {
        $initialData = $this->getInitialData();
        return view('pos.index', $initialData);
    }

    public function getInitialData()
    {
        $client = Client::first();
        $pharmacy = Pharmacy::first();
        $address = $client ? Address::where('client_id', $client->id)->first() : null;

        return [
            'client' => $client,
            'pharmacy' => $pharmacy,
            'address' => $address,
            'user' => $client ? User::find($client->user_id) : null,
        ];
    }

    public function getMedicines()
    {
        $medicines = Medicine::query();
        return DataTables::of($medicines)
            ->addColumn('action', function ($medicine) {
                return '<button type="button" class="btn btn-primary btn-sm add-to-cart" data-id="' . $medicine->id . '" data-name="' . $medicine->name . '" data-price="' . $medicine->price . '">Add</button>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'delivering_address_id' => 'required|exists:addresses,id',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $medicines = Medicine::whereIn('id', collect($request->items)->pluck('id'))->get()->keyBy('id');
        $totalPrice = 0;
        foreach ($request->items as $item) {
            $medicine = $medicines->get($item['id']);
            $totalPrice += $medicine->price * $item['quantity'];
        }

        $discount = $request->discount ?? 0;
        $finalPrice = ($totalPrice - $discount) / 100; // Assuming price is in cents for storage as dollars

        $order = Order::create([
            'user_id' => $request->user_id,
            'pharmacy_id' => $request->pharmacy_id,
            'delivering_address_id' => $request->delivering_address_id,
            'status' => 'Processing',
            'creator_type' => auth()->user()->getRoleNames()->first() ?? 'pharmacy',
            'price' => $finalPrice,
            'is_insured' => 0,
        ]);

        foreach ($request->items as $item) {
            $order->medicines()->attach($item['id'], ['quantity' => $item['quantity']]);
        }

        return response()->json(['success' => true, 'order_id' => $order->id]);
    }
}
