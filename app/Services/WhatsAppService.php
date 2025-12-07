<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function sendToGroup($message, $imageUrl = null)
    {
        $endpoint = "https://graph.facebook.com/v20.0/" . env('WHATSAPP_PHONE_ID') . "/messages";

        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "group",
            "to" => env('WHATSAPP_GROUP_ID'),
        ];

        if ($imageUrl) {
            $payload["type"] = "image";
            $payload["image"] = [
                "link" => $imageUrl,
                "caption" => $message,
            ];
        } else {
            $payload["type"] = "text";
            $payload["text"] = ["body" => $message];
        }

        return Http::withToken(env('WHATSAPP_ACCESS_TOKEN'))->post($endpoint, $payload);
    }
}
