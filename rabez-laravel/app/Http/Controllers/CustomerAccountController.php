<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAccountController extends Controller
{
    /**
     * Show the customer's account dashboard with order history and saved delivery profile.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $phone = User::normalizeBdPhone($user->phone);

        if ($phone) {
            $user->linkGuestOrdersByPhone($phone);
        }

        $orders = Order::with('items')
            ->where(function ($query) use ($user, $phone) {
                $query->where('user_id', $user->id);
                if ($phone) {
                    $query->orWhere('customer_phone', $phone);
                }
            })
            ->latest()
            ->get();

        $statusCounts = [
            'all' => $orders->count(),
            'pending' => $orders->where('status', 'Pending')->count(),
            'printing' => $orders->where('status', 'Printing')->count(),
            'shipped' => $orders->where('status', 'Shipped')->count(),
            'delivered' => $orders->where('status', 'Delivered')->count(),
            'cancelled' => $orders->where('status', 'Cancelled')->count(),
            'active' => $orders->whereIn('status', ['Pending', 'Printing', 'Shipped'])->count(),
        ];

        $stats = [
            'total_orders' => $statusCounts['all'],
            'pending_orders' => $statusCounts['pending'],
            'active_orders' => $statusCounts['active'],
            'delivered_orders' => $statusCounts['delivered'],
            'cancelled_orders' => $statusCounts['cancelled'],
            'total_spent' => (float) $orders->where('status', '!=', 'Cancelled')->sum('total'),
        ];

        $requestedStatus = strtolower(trim((string) $request->query('status', 'all')));
        $initialStatus = array_key_exists($requestedStatus, $statusCounts) ? $requestedStatus : 'all';

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('storefront.account', compact('user', 'orders', 'stats', 'statusCounts', 'initialStatus', 'settings'));
    }

    /**
     * Update customer's name, BD phone number, and default delivery address.
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^(?:\+?88)?(01[3-9]\d{8})$/'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'phone.regex' => 'Please enter a valid 11-digit Bangladesh mobile number (e.g. 01712345678).',
        ]);

        $phone = User::normalizeBdPhone($validated['phone']);
        /** @var User $user */
        $user = Auth::user();

        $user->update([
            'name' => strip_tags(trim($validated['name'])),
            'phone' => $phone,
            'address' => !empty($validated['address']) ? strip_tags(trim($validated['address'])) : null,
        ]);

        if ($phone) {
            $user->linkGuestOrdersByPhone($phone);
        }

        return redirect()->route('account.dashboard')
            ->with('success', 'Your profile and delivery details have been updated successfully!');
    }

    /**
     * Update the authenticated customer's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'The current password you entered is incorrect.',
            'password.min' => 'Your new password must be at least 8 characters long.',
            'password.confirmed' => 'New password confirmation does not match.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('account.dashboard')
            ->with('success', 'Your password has been updated successfully!');
    }
}
