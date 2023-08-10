<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case pending = "Pendente";
    case approved = "Aprovado";
    case denied = "Reprovado";
    case processing = "Em processamento";
    case paid = "Pago";

    public static function badge($name)
    {
        $case = collect(TransactionStatus::cases())->where("name", $name)->first();
        $type = match ($name) {
            "pending" => "info",
            "approved" => "primary",
            "denied" => "danger",
            "processing" => "warning",
            "paid" => "success",
        };
        return makeBadge($type, $case->value);
    }
}
