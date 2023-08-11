<?php

namespace App\Http\Resources;

use App\Http\Models\AccessGroup;
use App\User;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;
use Auth;

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

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-users');
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
        return ["name", "email"];
    }

    public function table()
    {
        return [
            "code" => ["label" => "#", "sortable_index" => "id", "width" => "80px"],
            "name" => ["label" => "Nome", "handler" => function ($row) {
                $activate = $row->email_verified_at ? "Ativado em : " . $row->email_verified_at->format("d/m/Y") : "Ainda não ativado";
                return Vstack::makeLinesHtmlAppend($row->firstName, $row->name, $row->email, $activate);
            }],
            "formatedProvider" => ["label" => "Provedor", "sortable_index" => "provider"],
            "access_level" => ["label" => "Nivel de acesso", "sortable" => false, "handler" => function ($row) {
                $role = $row->role;
                $group = "Acesso total";
                $level = 100;
                if ($role !== "admin") {
                    $accessGroup = $row->accessGroup;
                    $role = $accessGroup?->name ?? "Sem grupo de acesso";
                    $level = $accessGroup?->level ?? 0;
                }
                return Vstack::makeLinesHtmlAppend(makeProgress($level), "Grupo de Acesso : $group", "Tipo : $role");
            }],
        ];
    }

    public function fields()
    {
        $content = request()?->content;
        $user = Auth::user();

        $fields[] = new Text([
            "label" => "Nome",
            "description" => "Nome completo",
            "field" => "name",
            "required" => true,
            "rules" => "required|min:3|max:255",
        ]);

        $fields[] = new Text([
            "label" => "Email",
            "description" => "Este email será usado para acessar o sistema",
            "field" => "email",
            "required" => ['required', 'email', 'unique:users,email,' . ($content?->id ?? null)],
            "disabled" => $this->isEditing(),
        ]);
        $cards[] = new Card("Informações básicas", $fields);


        if (!$content || $content?->role !== "admin") {
            $fields = [];
            $fields[] = new BelongsTo([
                "label" => "Grupo de acesso",
                "description" => "Selecione um grupo de acesso para este usuário",
                "field" => "access_group_ids",
                "multiple" => true,
                "model" => AccessGroup::class

            ]);
            $cards[] = new Card("Nivel de acesso", $fields);
        }

        if ($user->hasPermissionTo("reset-credentials") && (!$content || $content?->provider === null)) {
            $passRequired = $this->isCreating() ? 'required' : 'nullable';
            $fields = [];
            $fields[] = new Text([
                "label" => "Senha",
                "type" => "password",
                "description" => "Digite uma nova senha para este usuário, caso não queira alterar a senha, deixe este campo em branco",
                "field" => "password",
                'rules' => [$passRequired, function ($att, $val, $fail) {
                    if ($val && !preg_match(User::PASS_HEGEX_VALIDATOR, $val)) {
                        $fail(User::PASS_VALIDATOR_MESSAGE);
                    }
                }],
            ]);
            $fields[] = new Text([
                "label" => "Confirme a senha",
                "type" => "password",
                "description" => "Confirme a senha digitada acima",
                "field" => "confirm_password",
                'rules' => [$passRequired, 'same:password']
            ]);

            $cards[] = new Card("Credenciais", $fields);
        }
        return $cards;
    }

    public function storeMethod($id, $data)
    {
        $access_groups = [];
        if (isset($data["data"]["access_group_ids"])) {
            $access_groups = $data["data"]["access_group_ids"];
            unset($data["data"]["access_group_ids"]);
        }
        if (isset($data["data"]["confirm_password"])) {
            unset($data["data"]["confirm_password"]);
        }
        $password = @$data["data"]["password"];
        if (isset($data["data"]["password"])) {
            unset($data["data"]["password"]);
        }

        $result = parent::storeMethod($id, $data);
        $model = $result["model"];

        if ($password) {
            $model->password = $password;
            if ($this->isCreating()) {
                $loggedUser = Auth::user();
                $model->email_verified_at = now();
                $model->plan = $loggedUser->plan;
            }
            $model->save();
        }

        $model->accessGroups()->sync($access_groups);

        return $result;
    }
}
