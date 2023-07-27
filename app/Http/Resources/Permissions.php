<?php

namespace App\Http\Resources;

use App\Http\Models\Permission;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Resource;

class Permissions extends Resource
{
    public $model = Permission::class;

    public function label()
    {
        return "Permissões";
    }

    public function singularLabel()
    {
        return "Permissão";
    }

    public function icon()
    {
        return "el-icon-lock";
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('canview-permissions');
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
        return false;
    }

    public function canViewReport()
    {
        return false;
    }

    public function search()
    {
        return ["name", "key"];
    }

    public function table()
    {
        return [
            "code" => ["label" => "#", "sortable_index" => "id", "width" => "80px"],
            "name" => ["label" => "Nome", "width" => "200px"],
            "key" => ["label" => "Chave", "width" => "100px"],
        ];
    }
}
