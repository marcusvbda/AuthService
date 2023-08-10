<?php

namespace App\Http\Resources;

use App\Enums\DemandStatus;
use App\Http\Models\Customer;
use App\Http\Models\Demand;
use App\Http\Models\Partner;
use App\Http\Models\Project;
use App\Http\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Filters\FilterByOption;
use marcusvbda\vstack\Filters\FilterByText;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Demands extends Resource
{
    public $model = Demand::class;

    public function label()
    {
        return "Demandas";
    }

    public function singularLabel()
    {
        return "Demanda";
    }

    public function icon()
    {
        return "el-icon-guide";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-demands');
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-demands');
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-demands');
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-demands');
    }

    public function canViewReport()
    {
        return false;
    }

    public function search()
    {
        return ["name"];
    }

    public function table()
    {
        return [
            "code" => ["label" => "#", "sortable_index" => "id"],
            "name" => ["label" => "Nome", "handler" => function ($row) {
                $customerName = $row->customer->name;
                $projectName = $row->project->name;
                return Vstack::makeLinesHtmlAppend($row->name, "Cliente : $customerName", "Projeto : $projectName");
            }],
            "start_date" => ["label" => "Início/Entrega", "handler" => function ($row) {
                $startDate = $row->start_date->format("d/m/Y") ?? "Não definido";
                $endDate = $row->end_date?->format("d/m/Y") ?? "Não definido";
                return Vstack::makeLinesHtmlAppend("Início : $startDate", "Entrega :$endDate");
            }],
            "partner" => ["label" => "Status", "sortable" => false, "handler" => function ($row) {
                return $row->partner->name ?? "Não definido";
            }],
            "f_status" => ["label" => "Parceiro", "sortable_index" => "status"],
            "f_created_at_badge" => ["label" => "Data do cadastro", "width" => "200px", "sortable_index" => "created_at"],
        ];
    }

    public function fields()
    {
        $cards = [];
        $fields[] = new Text([
            "label" => "Nome",
            "field" => "name",
            "required" => true,
            "description" => "Digite o nome da demanda",
        ]);

        if ($this->isEditing()) {
            $fields[] =  new BelongsTo([
                "label" => "Situação",
                "field" => "status",
                "description" => "Situação da demanda",
                "default" => $this->isCreating() ? DemandStatus::opened : "",
                "disabled" => $this->isCreating(),
                "options"   => Vstack::enumToOptions(DemandStatus::cases()),
            ]);
        }

        $fields[] =  new BelongsTo([
            "label" => "Cliente",
            "field" => "customer_id",
            "required" => true,
            "description" => "Selecione um cliente para ver seus projetos",
            "model"   => Customer::class
        ]);

        $fields[] = new BelongsTo([
            "label" => "Projeto",
            "field" => "project_id",
            "description" => "Projetos do cliente selecionado",
            "model"   => Project::class,
            "required" => true,
            "entity_parent" => "customer_id",
            "entity_parent_message" => "Selecione um cliente para selecionar um projeto",
        ]);

        $fields[] =  new Text([
            "label" => "Data início",
            "field" => "start_date",
            "type" => "date",
            "required" => true,
        ]);

        $fields[] =   new Text([
            "label" => "Data de entrega",
            "field" => "end_date",
            "type" => "date",
            "required" => true,
        ]);

        $fields[] =  new Text([
            "label" => "Url do briefing",
            "field" => "briefing_url",
        ]);

        $fields[] = new Text([
            "label" => "Budget",
            "field" => "budget",
            "type" => "currency",
            "default" => 0
        ]);

        $fields[] =  new Text([
            "label" => "Observações",
            "field" => "obs",
            "type" => "textarea",
            "rows" => 6
        ]);

        $cards[] = new Card("Informações da demanda", $fields);

        $fields = [];
        $fields[] =  new BelongsTo([
            "label" => "Habilidades",
            "field" => "skill_ids",
            "description" => "Selecione as habilidades para listar os parceiro as atende",
            "model" => Skill::class,
            "multiple" => true,
        ]);

        $fields[] =  new BelongsTo([
            "label" => "Parceiro",
            "field" => "partner_id",
            "description" => "Parceiros que atendem as habilidades selecionadas",
            "model"   => Partner::class,
            "entity_parent" => "skill_ids",
            "entity_parent_message" => "Selecione as habilidades para selecionar um parceiro",
            "fetch_options_calllback" => function (Request $request, $query) {
                return $query->whereHas("skills", function ($query) use ($request) {
                    $query->whereIn("skills.id", $request->skill_ids);
                });
            }
        ]);

        $fields[] =  new Text([
            "label" => "Observações",
            "field" => "partner_obs",
            "type" => "textarea",
            "rows" => 6
        ]);

        $cards[] = new Card("Informações do parceiro", $fields);
        return $cards;
    }

    public function storeMethod($id, $data)
    {
        $skill_ids = $data["data"]["skill_ids"] ?? [];
        unset($data["data"]["skill_ids"]);
        $result = parent::storeMethod($id, $data);
        if (!$result["success"]) {
            return $result;
        }
        $model = $result["model"];
        $model->syncSkills($skill_ids);
        return $result;
    }

    public function filters()
    {
        return [
            new FilterByText([
                "label" => "Nome da demanda",
                "field" => "name",
            ]),
            new FilterByOption([
                "label" => "Cliente",
                "field" => "customer_id",
                "model" => Customer::class,
                "multiple" => true,
            ]),
            new FilterByOption([
                "label" => "Projeto",
                "field" => "project_id",
                "model" => Project::class,
                "multiple" => true,
            ]),
            new FilterByOption([
                "label" => "Parceiro",
                "field" => "partner_id",
                "model" => Partner::class,
                "multiple" => true,
            ]),
            new FilterByOption([
                "label" => "Status",
                "field" => "status",
                "options" => Vstack::enumToOptions(DemandStatus::cases(), true),
                "multiple" => true,
            ]),
        ];
    }

    public function canViewAudits()
    {
        $auditsIsEnabled = parent::canViewAudits();
        return $auditsIsEnabled && Auth::user()->hasPermissionTo('view-audits-demands');
    }

    public function relatedResources()
    {
        return [
            new Transactions(["relation_fk" => "demand_id", 'order_by' => ['installment_id', 'asc']]),
        ];
    }
}
