<?php

namespace App\Http\Resources;

use App\Http\Models\Competence;
use App\Http\Models\Partner;
use App\Http\Models\Skill;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Filters\FilterByOption;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Partners extends Resource
{
    public $model = Partner::class;

    public function label()
    {
        return "Parceiros";
    }

    public function singularLabel()
    {
        return "Parceiro";
    }

    public function icon()
    {
        return "el-icon-user";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-partners');
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-partners');
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-partners');
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-partners');
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
                $partnerSince = date("d/m/Y", strtotime($row->partner_since));
                return Vstack::makeLinesHtmlAppend($row->name, $row->phone, "Parceiro desde $partnerSince", $row->email, $row->f_skill_competence);
            }],
            "f_price_hour" => ["label" => "Preço por hora", "sortable_index" => "price_hour"],
            "f_created_at_badge" => ["label" => "Data do cadastro", "width" => "200px", "sortable_index" => "created_at"],
        ];
    }

    public function fields()
    {
        return [
            new Card("Informações do parceiro", [
                new Text([
                    "label" => "Nome",
                    "field" => "name",
                    "required" => true,
                    "description" => "Digite o nome do parceiro",
                ]),
                new Text([
                    "label" => "Email",
                    "field" => "email",
                    "description" => "Digite o email da parceiro",
                ]),
                new Text([
                    "label" => "Telefone",
                    "field" => "phone",
                    "mask" => ["(##) ####-####", "(##) #####-####"],
                ]),
                new Text([
                    "label" => "Portifóilio",
                    "type" => "text",
                    "field" => "portifolio",
                ]),
                new Text([
                    "label" => "Preço por hora",
                    "type" => "currency",
                    "field" => "price_hour",
                    "default" => 0
                ]),
            ]),
            new Card("Dados de contrato", [
                new Text([
                    "label" => "Contrato",
                    "type" => "text",
                    "field" => "contract_url",
                ]),
                new Text([
                    "label" => "Parceiro desde",
                    "type" => "date",
                    "field" => "partner_since",
                    "default" => date("Y-m-d"),
                    "rules" => ["required", "date"]
                ]),
                new Text([
                    "label" => "Vencimento do contrato",
                    "type" => "date",
                    "field" => "contract_due_date",
                    "rules" => ["nullable", "date"]
                ]),
            ]),
            new Card("Habilidades e outras informações", [
                new BelongsTo([
                    "label" => "Habilidades",
                    "description" => "Habilidades e competências",
                    "multiple" => true,
                    "field" => "skill_ids",
                    "model" => Skill::class,
                    "model_fields" => [
                        "id" => "id",
                        "name" => "name",
                        "competence_id" => "competence_id",
                    ],
                    "option_template" => "<div class='flex flex-col'><b>{{item.name}} </b><small class='text-xs'>{{item.competence_name}}</small></div>",
                ]),
                new Text([
                    "label" => "Observações",
                    "type" => "textarea",
                    "field" => "obs",
                    "rows" => 5,
                ]),
            ]),
            new Card("Dados bancários", [
                new Text([
                    "label" => "Nome para emissão de nota fiscal",
                    "type" => "text",
                    "field" => "nfe_name",
                ]),
                new Text([
                    "label" => "CNPJ",
                    "type" => "text",
                    "field" => "doc_number",
                    "mask" => ["###.###.###-##", "##.###.###/####-##"],
                ]),
                new Text([
                    "label" => "Informações bancárias",
                    "type" => "textarea",
                    "field" => "bank_info",
                    "rows" => 5,
                ]),
            ]),
        ];
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

    public function filters()
    {
        return [
            new FilterByOption([
                "label" => "Habilidades",
                "field" => "skill_ids",
                "model" => Skill::class,
                "multiple" => true,
                "handle" => function ($query, $value) {
                    return $query->whereHas("skills", function ($query) use ($value) {
                        $values = explode(",", $value);
                        $query->whereIn("skill_id", $values);
                    });
                }
            ]),
            new FilterByOption([
                "label" => "Competências",
                "field" => "competence_id",
                "model" => Competence::class,
                "multiple" => true,
                "handle" => function ($query, $value) {
                    return $query->whereHas("skills", function ($query) use ($value) {
                        $values = explode(",", $value);
                        $query->whereIn("competence_id", $values);
                    });
                }
            ])
        ];
    }

    public function canViewAudits()
    {
        $auditsIsEnabled = parent::canViewAudits();
        return $auditsIsEnabled;
    }
}
