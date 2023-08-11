<?php

namespace App\Http\Actions\Financial;

use App\Enums\TransactionStatus;
use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{Transaction};
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Vstack;
use marcusvbda\vstack\Services\Messages;

class ChangeStatus extends Action
{
    public $run_btn = "Alterar";
    public $title = "Alterar status";
    public $message = "Alterar status dos itens selecionados";

    public function inputs()
    {
        $fields = [];
        $fields[] = new BelongsTo([
            "label" => "Status",
            "field" => "status",
            "options" => Vstack::enumToOptions(TransactionStatus::cases()),
            "rules" => ["required"],
        ]);
        $cards = [];
        $cards[] = new Card("Informações", $fields);
        return $cards;
    }

    public function handler(Request $request)
    {
        $ids = $request?->ids ?? [];
        $status = $request?->status;
        Transaction::whereIn("id", $ids)->update(["status" => $status]);
        Messages::send("success", "Status alterado com sucesso");
        return ['success' => true];
    }
}
