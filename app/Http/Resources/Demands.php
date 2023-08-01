<?php

namespace App\Http\Resources;

use App\Enums\DemandStatus;
use App\Http\Models\Competence;
use App\Http\Models\Customer;
use App\Http\Models\Demand;
use App\Http\Models\Partner;
use App\Http\Models\Project;
use App\Http\Models\Skill;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Filters\FilterByOption;
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
        return [
            new Card(
                "Informações da demanda",
                [
                    new Text([
                        "label" => "Nome",
                        "field" => "name",
                        "required" => true,
                        "description" => "Digite o nome da demanda",
                    ]),
                    new BelongsTo([
                        "label" => "Situação",
                        "field" => "status",
                        "description" => "Situação da demanda",
                        "options"   => Vstack::enumToOptions(DemandStatus::cases()),
                    ]),
                    new BelongsTo([
                        "label" => "Cliente",
                        "field" => "customer_id",
                        "description" => "Selecione um cliente para ver seus projetos",
                        "model"   => Customer::class
                    ]),
                    new BelongsTo([
                        "label" => "Projeto",
                        "field" => "project_id",
                        "description" => "Projetos do cliente selecionado",
                        "model"   => Project::class,
                        "entity_parent" => "customer_id",
                        "entity_parent_message" => "Selecione um cliente para selecionar um projeto",
                    ])
                ]
            )
        ];
    }
}
