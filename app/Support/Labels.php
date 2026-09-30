<?php

namespace App\Support;

class Labels
{
    public static function role(?string $value): string
    {
        return [
            'admin' => 'Админ',
            'operator' => 'Оператор',
            'agent' => 'Инженер',
            'customer' => 'Харилцагч',
        ][$value] ?? (string) $value;
    }

    public static function status(?string $value): string
    {
        return [
            'submitted' => 'Илгээгдсэн',
            'accepted' => 'Хүлээн авсан',
            'resolved' => 'Шийдвэрлэсэн',
        ][$value] ?? (string) $value;
    }

    public static function type(?string $value): string
    {
        return [
            'network' => 'Сүлжээ',
            'program' => 'Программ',
            'software' => 'Программ',
            'hardware' => 'Тоног төхөөрөмж',
            'other' => 'Бусад',
        ][$value] ?? (string) $value;
    }

    public static function accountStatus(?string $value): string
    {
        return [
            'active' => 'Идэвхтэй',
            'inactive' => 'Идэвхгүй',
        ][$value] ?? (string) $value;
    }

    public static function action(?string $value): string
    {
        return [
            'login' => 'Системд нэвтэрсэн',
            'logout' => 'Системээс гарсан',
            'register' => 'Шинээр бүртгүүлсэн',
        ][$value] ?? (string) $value;
    }
}
