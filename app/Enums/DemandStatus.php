<?php

namespace App\Enums;

enum DemandStatus: string
{
    case OPENED = "Aberto";
    case DOING = "Em andamento";
    case FINISHED = "Finalizado";
    case DELIVERED = "Entregue";
}
