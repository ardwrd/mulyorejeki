<?php

namespace App\Libraries;

final class StoreContact
{
    public static function whatsappUrl(string $message = ''): ?string
    {
        $number = preg_replace('/\D+/', '', (string) env('store.whatsappNumber', ''));

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        if (! preg_match('/^[1-9][0-9]{8,14}$/', $number)) {
            return null;
        }

        $url = 'https://wa.me/' . $number;

        return $message === '' ? $url : $url . '?text=' . rawurlencode($message);
    }
}
