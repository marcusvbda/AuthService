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
            // "name" => ["label" => "Nome", "handler" => function ($row) {
            //     $demandsince = date("d/m/Y", strtotime($row->partner_since));
            //     return Vstack::makeLinesHtmlAppend($row->name, $row->phone, "Parceiro desde $demandsince", $row->email, $row->f_skill_competence);
            // }],
            // "f_price_hour" => ["label" => "Preço por hora", "sortable_index" => "price_hour"],
            // "f_created_at_badge" => ["label" => "Data do cadastro", "width" => "200px", "sortable_index" => "created_at"],
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
                "default" => $this->isCreating() ? DemandStatus::OPENED : "",
                "disabled" => $this->isCreating(),
                "options"   => Vstack::enumToOptions(DemandStatus::cases()),
            ]);
        }

        $fields[] =  new BelongsTo([
            "label" => "Cliente",
            "field" => "customer_id",
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
        if (!$result["success"]) return $result;
        $model = $result["model"];
        $model->syncSkills($skill_ids);
        return $result;
    }
}
