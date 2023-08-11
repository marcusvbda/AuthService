<?php

namespace App\Http\Resources;

use App\Http\Models\Squad;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Squads extends Resource
{
    public $model = Squad::class;

    public function label()
    {
        return "Squads";
    }

    public function singularLabel()
    {
        return "Squad";
    }

    public function icon()
    {
        return "el-icon-s-claim";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-squads');
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-squads');
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-squads');
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-squads');
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
            "name" => ["label" => "Nome"],
            "f_created_at_badge" => ["label" => "Criado em", "sortable_index" => "created_at"],
        ];
    }

    public function fields()
    {
        return [
            new Card("Informações do squad", [
                new Text([
                    "label" => "Nome",
                    "field" => "name",
                    "required" => true,
                    "description" => "Digite o nome do squad",
                ]),
            ]),
        ];
    }
}
