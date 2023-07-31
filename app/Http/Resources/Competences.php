<?php

namespace App\Http\Resources;

use App\Http\Models\Competence;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Fields\Tags;
use marcusvbda\vstack\Resource;

class Competences extends Resource
{
    public $model = Competence::class;

    public function label()
    {
        return "Competências";
    }

    public function singularLabel()
    {
        return "Competência";
    }

    public function icon()
    {
        return "el-icon-s-management";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-competences');
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-competences');
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-competences');
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-competences');
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
            "skills" => ["label" => "Nome", "width" => "200px", "sortable" => false],
            "f_created_at_badge" => ["label" => "Nome", "width" => "200px", "sortable_index" => "created_at"],
        ];
    }

    public function fields()
    {
        return [
            new Card("Informações básicas", [
                new Text([
                    "label" => "Nome",
                    "field" => "name",
                    "required" => true,
                    "description" => "Digite o nome da competência",
                ]),
                new Tags([
                    "label" => "Habilidades",
                    "field" => "skills",
                    "description" => "Habilidades relacionadas a esta competência",
                ]),
            ]),
        ];
    }
}
