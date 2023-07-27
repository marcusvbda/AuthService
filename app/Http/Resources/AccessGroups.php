<?php

namespace App\Http\Resources;

use App\Http\Models\AccessGroup;
use App\Http\Models\Permission;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Resource;

class AccessGroups extends Resource
{
    public $model = AccessGroup::class;

    public function label()
    {
        return "Grupos de acesso";
    }

    public function singularLabel()
    {
        return "Grupo de acesso";
    }

    public function icon()
    {
        return "el-icon-lock";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-access-groups');
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-access-groups');
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-access-groups');
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
            "name" => ["label" => "Nome", "width" => "200px"],
            "level" => ["label" => "Nível de liberdade", "sortable" => false, "handler" => function ($object) {
                $totalPermissions = Permission::count();
                $totalPermissionsByGroup = $object->permissions()->count();
                $percentage = round(($totalPermissionsByGroup * 100) / $totalPermissions, 2);
                return "<el-progress :text-inside='true' :stroke-width='26' :percentage='$percentage'></el-progress>";
            }],
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
                    "description" => "Digite o nome do grupo de acesso",
                ]),
                new BelongsTo([
                    "label" => "Permissões",
                    "field" => "permission_ids",
                    "multiple" => true,
                    "model" => Permission::class,
                    "description" => "Selecione as permissões que esse grupo de acesso terá",
                ])
            ])
        ];
    }

    public function storeMethod($id, $data)
    {
        $permissions = $data["data"]["permission_ids"];
        unset($data["data"]["permission_ids"]);

        $result = parent::storeMethod($id, $data);
        $result["model"]->permissions()->sync($permissions);
        return $result;
    }
}
