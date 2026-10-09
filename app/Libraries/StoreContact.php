<?php

namespace App\Libraries;

final class StoreContact
{
    public const PHONE_DISPLAY = '0812-2802-283';

    public const PHONE_E164 = '628122802283';

    public const ADDRESS = 'Jl. K.H. Agus Salim, Purwodinatan, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50137';

    public const WEEKDAY_HOURS = 'Senin–Jumat 08.00–16.00 WIB';

    public const SATURDAY_HOURS = 'Sabtu 08.00–14.00 WIB';

    public const SUNDAY_HOURS = 'Minggu tutup';

    public static function whatsappUrl(string $message = ''): ?string
    {
        $number = self::number();

        if ($number === null) {
            return null;
        }

        $url = 'https://wa.me/' . $number;

        return $message === '' ? $url : $url . '?text=' . rawurlencode($message);
    }

    public static function phoneDisplay(): ?string
    {
        $number = self::number();

        return $number === null ? null : ($number === self::PHONE_E164 ? self::PHONE_DISPLAY : '+' . $number);
    }

    public static function phoneUrl(): ?string
    {
        $number = self::number();

        return $number === null ? null : 'tel:+' . $number;
    }

    public static function mapsUrl(): string
    {
        return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(self::ADDRESS);
    }

    private static function number(): ?string
    {
        $configuredNumber = trim((string) env('store.whatsappNumber', ''));
        $number = preg_replace('/\D+/', '', $configuredNumber !== '' ? $configuredNumber : self::PHONE_E164);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        if (! preg_match('/^[1-9][0-9]{8,14}$/', $number)) {
            return null;
        }

        return $number;
    }
}
