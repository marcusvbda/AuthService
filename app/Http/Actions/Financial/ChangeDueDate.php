<?php

namespace App\Http\Actions\Financial;

use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{Transaction};
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Services\Messages;

class ChangeDueDate extends Action
{
    public $run_btn = "Alterar";
    public $title = "Alterar vencimento";
    public $message = "Alterar vencimento dos itens selecionados";

    public function inputs()
    {
        $fields = [];
        $fields[] = new Text([
            "type" => "date",
            "label" => "Vencimento",
            "field" => "due_date",
            "rules" => ["required"],
        ]);
        $cards = [];
        $cards[] = new Card("Informações", $fields);
        return $cards;
    }

    public function handler(Request $request)
    {
        $ids = $request?->ids ?? [];
        $due_date = $request?->due_date;
        Transaction::whereIn("id", $ids)->update(["due_date" => $due_date]);
        Messages::send("success", "Vencimento alterado com sucesso");
        return ['success' => true];
    }
}
