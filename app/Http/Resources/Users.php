<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Users extends Resource
{
    public $model = User::class;

    public function label()
    {
        return "Usuários";
    }

    public function singularLabel()
    {
        return "Usuário";
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
        return Auth::user()->hasPermissionTo('create-users');
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-update-users');
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-users');
    }

    public function canViewReport()
    {
        return false;
    }

    public function canDeleteRow($row)
    {
        return $row && $row->role !== "admin";
    }

    public function canUpdateRow($row)
    {
        $user = Auth::user();
        return $row && ($row->role !== "admin" || $row->id === $user->id);
    }

    public function search()
    {
        return ["name"];
    }

    public function table()
    {
        return [
            "code" => ["label" => "#", "sortable_index" => "id", "width" => "80px"],
            "name" => ["label" => "Nome", "handler" => function ($row) {
                return Vstack::makeLinesHtmlAppend($row->firstName, $row->name, $row->email);
            }],
            "email_verified_at" => ["label" => "Ativado em", "sortable_index" => "email_verified_at", "handler" => function ($row) {
                return $row->email_verified_at ? $row->email_verified_at->format("d/m/Y") : "Ainda não ativado";
            }],
            "access_level" => ["label" => "Nivel de acesso", "sortable" => false, "handler" => function ($row) {
                $role = $row->role;
                $level = 100;
                if ($role !== "admin") {
                    $accessGroup = $row->accessGroup;
                    $role = $accessGroup?->name ?? "Sem grupo de acesso";
                    $level = $accessGroup?->level ?? 0;
                }
                return Vstack::makeLinesHtmlAppend($role, makeProgress($level));
            }],
        ];
    }
}
