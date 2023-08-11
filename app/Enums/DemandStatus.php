<?php

namespace App\Enums;

enum DemandStatus: string
{
    case open = "Aberto";
    case inprogress = "Em andamento";
    case finish = "Finalizado";
    case delivered = "Entregue";

    public static function badge($name)
    {
        $case = collect(static::cases())->where("name", $name)->first();
        $type = match ($name) {
            "open" => "info",
            "inprogress" => "warning",
            "finish" => "primary",
            "delivered" => "success",
        };
        return makeBadge($type, $case->value);
    }
}
