<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaCapiService
{
    /**
     * Send a deduplicated server-side Purchase event to Meta Conversions API
     * when both meta_pixel_id and meta_capi_token are configured.
     */
    public function sendPurchaseEvent(Order $order, array $items, Request $request): void
    {
        $pixelId = preg_replace('/[^0-9]/', '', (string) Setting::get('meta_pixel_id', ''));
        $token = trim((string) Setting::get('meta_capi_token', ''));

        if ($pixelId === '' || $token === '') {
            return;
        }

        $digits = preg_replace('/[^0-9]/', '', (string) $order->customer_phone);
        if (strlen($digits) === 11 && str_starts_with($digits, '01')) {
            $digits = '88' . $digits;
        }

        $userData = array_filter([
            'client_ip_address' => $request->ip(),
            'client_user_agent' => $request->userAgent(),
            'ph' => $digits !== '' ? [hash('sha256', $digits)] : null,
        ]);

        $contentIds = [];
        $numItems = 0;
        foreach ($items as $item) {
            if (!empty($item['id'])) {
                $contentIds[] = (string) $item['id'];
            }
            $numItems += max(1, (int) ($item['qty'] ?? 1));
        }

        $payload = [
            'data' => [
                [
                    'event_name' => 'Purchase',
                    'event_time' => time(),
                    'event_id' => $order->event_id ?: ('rbz_pur_' . $order->id),
                    'action_source' => 'website',
                    'event_source_url' => $request->headers->get('referer') ?: url('/checkout'),
                    'user_data' => $userData,
                    'custom_data' => [
                        'currency' => 'BDT',
                        'value' => round((float) $order->total, 2),
                        'content_type' => 'product',
                        'content_ids' => array_values(array_unique($contentIds)),
                        'num_items' => max(1, $numItems),
                        'order_id' => $order->order_number,
                    ],
                ],
            ],
        ];

        try {
            Http::timeout(3)->post(
                "https://graph.facebook.com/v21.0/{$pixelId}/events?access_token={$token}",
                $payload
            );
        } catch (\Throwable $e) {
            Log::warning('Meta CAPI Purchase dispatch failed: ' . $e->getMessage());
        }
    }
}
