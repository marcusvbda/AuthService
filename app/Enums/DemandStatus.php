<?php

namespace App\Enums;

enum DemandStatus: string
{
    case opened = "Aberto";
    case doing = "Em andamento";
    case finished = "Finalizado";
    case delivered = "Entregue";

    public static function badge($name)
    {
        $case = collect(static::cases())->where("name", $name)->first();
        $type = match ($name) {
            "opened" => "info",
            "doing" => "warning",
            "finished" => "primary",
            "delivered" => "success",
        };
        return makeBadge($type, $case->value);
    }
}
