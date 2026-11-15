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

        $stats = [
            'total_orders' => $orders->count(),
            'active_orders' => $orders->whereIn('status', ['Pending', 'Printing', 'Shipped'])->count(),
            'delivered_orders' => $orders->where('status', 'Delivered')->count(),
            'total_spent' => (float) $orders->where('status', '!=', 'Cancelled')->sum('total'),
        ];

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('storefront.account', compact('user', 'orders', 'stats', 'settings'));
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
            'phone.regex' => 'অনুগ্রহ করে সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 01712345678)',
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
            ->with('success', 'আপনার প্রোফাইল ও ডেলিভারি তথ্য সফলভাবে আপডেট হয়েছে!');
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
            'current_password.current_password' => 'বর্তমান পাসওয়ার্ডটি সঠিক নয়।',
            'password.min' => 'নতুন পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।',
            'password.confirmed' => 'নতুন পাসওয়ার্ড দুটি মেলেনি।',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('account.dashboard')
            ->with('success', 'আপনার পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে!');
    }
}
