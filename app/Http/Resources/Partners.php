<?php

namespace App\Http\Resources;

use App\Http\Models\Competence;
use App\Http\Models\Partner;
use App\Http\Models\Skill;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Resource;

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
            "name" => ["label" => "Nome"],
            "f_created_at_badge" => ["label" => "Nome", "width" => "200px", "sortable_index" => "created_at"],
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
                    "prepend" => "R$ ",
                    "type" => "number",
                    "step" => "0.01",
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
                    "label" => "Vencimento do contrato",
                    "type" => "date",
                    "field" => "contract_due_date",
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
        $skill_ids = $data["data"]["skill_ids"];
        unset($data["data"]["skill_ids"]);
        $result = parent::storeMethod($id, $data);
        $model = $result["model"];
        $model->syncSkills($skill_ids);
        return $result;
    }

    public function beforeListSlot()
    {
        return view("admin.partners.before-list-slot");
    }
}
