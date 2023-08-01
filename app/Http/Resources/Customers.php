<?php

namespace App\Http\Resources;

use App\Http\Models\Customer;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Customers extends Resource
{
    public $model = Customer::class;

    public function label()
    {
        return "Clientes";
    }

    public function singularLabel()
    {
        return "Cliente";
    }

    public function icon()
    {
        return "el-icon-s-custom";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-customers');
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-customers');
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-customers');
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-customers');
    }

    public function canViewReport()
    {
        return false;
    }

    public function search()
    {
        return ["name", "responsable_name", "responsable_email", "responsable_phone", "website"];
    }

    public function table()
    {
        return [
            "code" => ["label" => "#", "sortable_index" => "id", "width" => "80px"],
            "label" => ["label" => "Nome", "width" => "200px", "handler" => function ($row) {
                $link = $row->website ? "<a href='{$row->website}' target='_blank'>{$row->website}</a>" : "";
                return Vstack::makeLinesHtmlAppend($row->name, $link);
            }],
            "responsible_name" => ["label" => "Responsável"],
            "phone" => ["label" => "Telefone"],
            "f_created_at_badge" => ["label" => "Criado em", "sortable_index" => "created_at"],
        ];
    }

    public function fields()
    {
        return [
            new Card("Informações do cliente", [
                new Text([
                    "label" => "Nome",
                    "field" => "name",
                    "required" => true,
                    "description" => "Digite o nome do cliente",
                ]),
                new Text([
                    "label" => "Telefone",
                    "field" => "phone",
                    "description" => "Telefone para contato",
                    "mask" => ["(##) ####-####", "(##) #####-####"]
                ]),
                new Text([
                    "label" => "Site",
                    "field" => "website",
                ]),
                new Text([
                    "label" => "Guia de boas práticas",
                    "field" => "guide",
                ]),
            ]),
            new Card("Informações do responsável", [
                new Text([
                    "label" => "Nome",
                    "field" => "responsible_name",
                    "description" => "Digite o nome do responsável",
                ]),
                new Text([
                    "label" => "Email",
                    "field" => "responsible_email",
                    "description" => "Digite o nome do responsável",
                ]),
                new Text([
                    "label" => "Telefone",
                    "field" => "responsible_phone",
                    "description" => "Telefone do responsável",
                    "mask" => ["(##) ####-####", "(##) #####-####"]
                ]),
                new Text([
                    "label" => "Categoria de faturamento",
                    "field" => "billing_category",
                ]),
            ])
        ];
    }
}
