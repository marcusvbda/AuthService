<?php

namespace App\Http\Resources;

use App\Enums\TransactionStatus;
use App\Http\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\ComputedView;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Transactions extends Resource
{
    public $model = Transaction::class;

    public function label()
    {
        return "Pagamentos";
    }

    public function singularLabel()
    {
        return "Pagamento";
    }

    public function icon()
    {
        return "el-icon-money";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-transaction');
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-transaction');
    }

    public function canDelete()
    {
        return  Auth::user()->hasPermissionTo('delete-transaction');
    }

    public function canViewList()
    {
        return $this->isInRelatedResource() &&  Auth::user()->hasPermissionTo('viewlist-transaction');
    }

    public function canViewReport()
    {
        return false;
    }

    public function search()
    {
        return ["description"];
    }

    public function table()
    {
        return [
            "code" => ["label" => "#", "sortable_index" => "id"],
            "description" => ["label" => "Descrição", "handler" => function ($row) {
                return Vstack::makeLinesHtmlAppend($row->description, $row->ref);
            }],
            "installment_id" => ["label" => "Parcela", "sortable_index" => "installment_id"],
            "f_due_date" => ["label" => "Data de Pagto", "sortable_index" => "due_date"],
            "f_total_amount" => ["label" => "Valor", "sortable_index" => "installment_id", "handler" => function ($row) {
                return Vstack::makeLinesHtmlAppend("Valor da parcela : " . $row->f_total_amount, "Valor total : " . $row->f_installment_amount);
            }],
            "f_status" => ["label" => "Status", "sortable_index" => "status"],
        ];
    }

    public function canViewAudits()
    {
        $auditsIsEnabled = parent::canViewAudits();
        return $auditsIsEnabled && Auth::user()->hasPermissionTo('view-audits-demands');
    }

    public function fields()
    {
        $cards = [];
        if ($this->isEditing()) {
            $fields[] = new BelongsTo([
                "label" => "Status",
                "field" => "status",
                "options" => Vstack::enumToOptions(TransactionStatus::cases()),
                "rules" => ["required"],
            ]);
        }

        $fields[] = new Text([
            "label" => "Descrição",
            "field" => "description",
            "description" => "Para indefificar o pagamento",
            "required" => true,
        ]);

        $fields[] =  new Text([
            "label" => "Valor total",
            "field" => "total_amount",
            "type" => "currency",
            "disabled" => $this->isEditing(),
            "rules" => ["required", "min:1"],
        ]);

        $fields[] =  new Text([
            "label" => "Parcelas",
            "field" => "qty_installments",
            "type" => "number",
            "default" => 1,
            "disabled" => $this->isEditing(),
            "rules" => ["min:1"],
            "description" => "Quantidade de parcelas que serão pagas",
        ]);

        $fields[] = new ComputedView([
            "label" => "Valor da parcela",
            "description" => "Valor de cada parcela",
            "template" => '<b class="text-3xl dark:text-neutral-200">{{eval}}</b>',
            "eval" => "((!this.form.qty_installments || !this.form.total_amount) ? 0 : this.form.total_amount / this.form.qty_installments).currency()",
        ]);

        if ($this->isCreating()) {
            $fields[] = new Text([
                "label" => "Data do primeiro pagamento",
                "description" => "Essa mesma data será preservada para os próximos pagamentos (se houverem), apenas os meses serão alterados",
                "field" => "due_date",
                "type" => "date",
                'default' => now()->format("Y-m-d"),
                "required" => true,
            ]);
        }

        $cards[] = new Card("Informações do Pagamento", $fields);
        return $cards;
    }

    public function storeMethod($id, $data)
    {
        $installments = intval($data["data"]["qty_installments"]);
        $total_amount = $data["data"]["total_amount"];
        $installment_amount = $total_amount / $installments;
        $data['data']['installment_amount'] = $installment_amount;

        if ($this->isCreating()) {
            $due_date = $data["data"]["due_date"];
            $ref = uniqid();
            $data['data']['ref'] = $ref;
            for ($i = 1; $i <= $installments; $i++) {
                $due_date = date("Y-m-d", strtotime("+1 month", strtotime($due_date)));
                $data['data']['installment_id'] = $i . "/" . $installments;
                $data['data']['due_date'] = $due_date;
                parent::storeMethod($id, $data);
            }
        }

        return parent::storeMethod($id, $data);
    }
}
