<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function create($id)
    {

    if (session('last_item_id') != $id) {
        session()->forget(['payment_method', 'shipping_postal_code', 'shipping_address', 'shipping_building']);
    }
    session(['last_item_id' => $id]);

    

        $item = Item::with('images')->findOrFail($id);
        $user = Auth::user();

        $shipping_postal_code = session('shipping_postal_code', $user->postal_code);
        $shipping_address = session('shipping_address', $user->address);
        $shipping_building = session()-> exists('shipping_building') ? session('shipping_building') : $user->building;

        return view('purchases.create', compact('item', 'user', 'shipping_postal_code', 'shipping_address', 'shipping_building'));
    }

    public function savePaymentMethod(Request $request)
{
    session(['payment_method' => $request->payment_method]);
    return response()->json(['status' => 'ok']);
}

        
    public function editAddress(Request $request, $id) {
        if ($request->has('payment_method')) {
            session(['payment_method' => $request->query('payment_method')]);
            }

        $item = Item::findOrFail($id);
        $user = Auth::user();
        return view('purchases.edit_address', compact('item', 'user'));
    }


    public function updateAddress(AddressRequest $request, $id) 
    {

        $validated = $request->validated();

        $request->session()->put('shipping_postal_code', $validated['shipping_postal_code']);
        $request->session()->put('shipping_address', $validated['shipping_address']);
        $request->session()->put('shipping_building', $validated['shipping_building']);

        return redirect()->route('purchase.create', $id);
    }

    public function store(PurchaseRequest $request, $id)
    {
        $validated = $request->validated();

        $item = Item::with('images')->findOrFail($id);
        $user = Auth::user();

        $paymentMethodMap = [
            'カード支払い' => 'card',
            'コンビニ支払い' => 'konbini',
        ];

        $stripePaymentMethod = $paymentMethodMap[$validated['payment_method']];


        \Stripe\Stripe::setApiKey(config('stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => [$stripePaymentMethod],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('items.index'),
            'cancel_url' => route('purchase.create', $item->id),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => $user->id,
                'payment_method' => $validated['payment_method'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_building' => $validated['shipping_building'],
                'price' => $item->price,
            ],
        ]);

        return redirect()->away($session->url);
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            );
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $itemId = $session->metadata->item_id;
            $userId = $session->metadata->user_id;
            $paymentMethod = $session->metadata->payment_method;
            $paymentIntentId = $session->payment_intent;
            $postalCode = $session->metadata->shipping_postal_code;
            $address = $session->metadata->shipping_address;
            $building = $session->metadata->shipping_building;
            $price = $session->metadata->price;

            $purchase = Purchase::create([
                'item_id' => $itemId,
                'user_id' => $userId,
                'payment_method' => $paymentMethod,
                'stripe_payment_intent_id' => $paymentIntentId,
                'shipping_postal_code' => $postalCode,
                'shipping_address' => $address,
                'shipping_building' => $building,
                'price' => $price,
            ]);
            
            $item = Item::findOrFail($itemId);
            
            $item->update([
                'status' => 'sold',
            ]);                
        }

        return response('Webhook received', 200);
    }
}
