<?php

namespace App\Http\Resources;

use App\Enums\TransactionStatus;
use App\Http\Actions\Financial\ChangeDueDate;
use App\Http\Actions\Financial\ChangeStatus;
use App\Http\Models\Customer;
use App\Http\Models\Demand;
use App\Http\Models\Project;
use App\Http\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Actions\MultipleDelete;
use marcusvbda\vstack\Filters\FilterByOption;
use marcusvbda\vstack\Filters\FilterByPresetDate;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Financial extends Resource
{
    public $model = Transaction::class;

    public function label()
    {
        return "Financeiro";
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
        return false;
    }

    public function canUpdate()
    {
        return false;
    }

    public function canDelete()
    {
        return  false;
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-transaction');
    }

    public function canViewReport()
    {
        return Auth::user()->hasPermissionTo('report-transaction');
    }

    public function canExport()
    {
        return Auth::user()->hasPermissionTo('report-transaction');
    }

    public function table()
    {
        return [
            "code" => ["label" => "#", "sortable_index" => "id"],
            "description" => ["label" => "Descrição", "handler" => function ($row) {
                return Vstack::makeLinesHtmlAppend($row->description, $row->f_status, $row->ref);
            }, "size" => "250px"],
            "label" => ["label" => "Parceiro", "sortable" => false, "handler" => function ($row) {
                return @$row->demand->partner->name ?? "Sem parceiro definido";
            }, "size" => "250px"],
            "demand" => ["label" => "Projeto/Cliente/Demanda", "sortable" => false, "handler" => function ($row) {
                $projectName = @$row->demand->project->name ?? "Sem projeto definido";
                $customerName = @$row->demand->customer->name ?? "Sem cliente definido";
                $demandName = @$row->demand->name ?? "Sem demanda definida";
                return Vstack::makeLinesHtmlAppend($projectName, $customerName, $demandName);
            }, "size" => "250px"],
            "installment_id" => ["label" => "Parcela/Vencimento", "handler" => function ($row) {
                $dueDate = $row->f_due_date;
                $installmentId = $row->installment_id;
                return Vstack::makeLinesHtmlAppend($installmentId, $dueDate);
            }, "size" => "250px"],
            "f_total_amount" => ["label" => "Valor Parcela/Total", "sortable_index" => "installment_id", "handler" => function ($row) {
                return Vstack::makeLinesHtmlAppend($row->f_installment_amount, $row->f_total_amount);
            }],
        ];
    }

    public function exportColumns()
    {
        $fields[] = ['label' => 'Código', 'handler' => fn ($row) => $row->code];
        $fields[] = ['label' => 'Descrição', 'handler' => fn ($row) => $row->description];
        $fields[] = ['label' => 'Status', 'handler' => fn ($row) => TransactionStatus::translate($row->status)];
        $fields[] = ['label' => 'Ref', 'handler' => fn ($row) => $row->ref];
        $fields[] = ['label' => 'Parceiro', 'handler' => fn ($row) => @$row->demand->partner->name ?? "Sem parceiro definido"];
        $fields[] = ['label' => 'Projeto', 'handler' => fn ($row) => @$row->demand->project->name ?? "Sem projeto definido"];
        $fields[] = ['label' => 'Demanda', 'handler' => fn ($row) => @$row->demand->name ?? "Sem demanda definida"];
        $fields[] = ['label' => 'Vencimento', 'handler' => fn ($row) => @$row->f_due_date];
        $fields[] = ['label' => 'Parcela', 'handler' => fn ($row) => @$row->installment_id];
        $fields[] = ['label' => 'Total', 'handler' => fn ($row) => @$row->f_total_amount];
        $fields[] = ['label' => 'Valor do pagto', 'handler' => fn ($row) => @$row->f_installment_amount];
        return $fields;
    }


    public function canViewAudits()
    {
        return false;
    }

    public function filters()
    {
        $filters = [];
        $filters[] = new FilterByOption([
            "label" => "Status",
            "column" => "status",
            "options" => Vstack::enumToOptions(TransactionStatus::cases(), true),
            "multiple" => true
        ]);

        $filters[] = new FilterByPresetDate([
            "label" => "Data de Vencimento",
            "column" => "due_date",
        ]);

        $filters[] = new FilterByOption([
            "label" => "Projeto",
            "column" => "project_id",
            "multiple" => true,
            "model" => Project::class,
            "handle" => function ($query, $val) {
                return $query->whereHas("demand", function ($q) use ($val) {
                    $q->where("project_id", $val);
                });
            }
        ]);

        $filters[] = new FilterByOption([
            "label" => "Cliente",
            "column" => "customer_id",
            "multiple" => true,
            "model" => Customer::class,
            "handle" => function ($query, $val) {
                return $query->whereHas("demand", function ($q) use ($val) {
                    $q->where("customer_id", $val);
                });
            }
        ]);

        $filters[] = new FilterByOption([
            "label" => "Demands",
            "column" => "demand_id",
            "multiple" => true,
            "model" => Demand::class,
        ]);
        return $filters;
    }

    public function actions()
    {
        $actions = [];
        $actions[] = new ChangeStatus();
        $actions[] = new ChangeDueDate();
        return $actions;
    }
}
