<?php

namespace App\Http\Resources;

use App\Http\Models\Customer;
use App\Http\Models\Project;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Filters\FilterByOption;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Projects extends Resource
{
    public $model = Project::class;

    public function label()
    {
        return "Projetos";
    }

    public function singularLabel()
    {
        return "Projeto";
    }

    public function icon()
    {
        return "el-icon-s-opportunity";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-projects');
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-projects');
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-projects');
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-projects');
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
            "code" => ["label" => "#", "sortable_index" => "id", "width" => "80px"],
            "label" => ["label" => "Nome", "width" => "200px", "handler" => function ($row) {
                return Vstack::makeLinesHtmlAppend($row->name, $row?->customer?->name);
            }],
            "dates" => ["label" => "Duração", "width" => "200px", "handler" => function ($row) {
                return Vstack::makeLinesHtmlAppend($row->start_date->format("d/m/Y"), $row?->end_date?->format("d/m/Y") ?: "Não informado");
            }],
            "board" => ["label" => "Board"],
            "google_drive_url" => ["label" => "Drive"],
            "f_created_at_badge" => ["label" => "Criado em", "sortable_index" => "created_at"],
        ];
    }

    public function fields()
    {
        return [
            new Card("Informações do projeto", [
                new Text([
                    "label" => "Nome",
                    "field" => "name",
                    "required" => true,
                    "description" => "Digite o nome do projeto  ",
                ]),
                new BelongsTo([
                    "label" => "Cliente",
                    "field" => "customer_id",
                    "model" => Customer::class
                ]),
                new Text([
                    "label" => "Data de Início",
                    "field" => "start_date",
                    "required" => true,
                    "type" => "date"
                ]),
                new Text([
                    "label" => "Data de entrega",
                    "field" => "end_date",
                    "type" => "date"
                ]),
            ]),
            new Card("Links do cliente", [
                new Text([
                    "label" => "Board",
                    "field" => "board",
                    "description" => "Board ( InVision )",
                ]),
                new Text([
                    "label" => "Google Drive",
                    "field" => "google_drive_url",
                    "description" => "Pasta do google drive",
                ]),
            ])
        ];
    }

    public function filters()
    {
        return [
            new FilterByOption([
                "label" => "Cliente",
                "field" => "customer_id",
                "model" => Customer::class,
            ])
        ];
    }

    public function beforeListSlot()
    {
        return view("admin.projects.before-list-slot");
    }
}
