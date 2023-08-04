<?php

namespace App\Enums;

enum DemandStatus: string
{
    case opened = "Aberto";
    case doing = "Em andamento";
    case finished = "Finalizado";
    case delivered = "Entregue";
}
