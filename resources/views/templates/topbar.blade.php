@php
    $user = Auth::user();
    $canViewUsers = $user->hasPermissionTo('viewlist-users');
    $canViewAccessGroups = $user->hasPermissionTo('viewlist-access-groups');
    $canViewPermission = $user->hasPermissionTo('viewlist-permissions');
    $canViewCompetence = $user->hasPermissionTo('viewlist-competence');
    $canViewCustomers = $user->hasPermissionTo('viewlist-customers');
    $canViewProjects = $user->hasPermissionTo('viewlist-projects');
    $canViewPartners = $user->hasPermissionTo('viewlist-partners');
    
    $items = [
        [
            'position' => 'center',
            'title' => 'Projetos',
            'route' => '/admin/projects',
            'visible' => $canViewProjects,
            'items' => [],
        ],
        [
            'position' => 'center',
            'title' => 'Clientes',
            'route' => '/admin/customers',
            'visible' => $canViewCustomers,
            'items' => [],
        ],
        [
            'position' => 'center',
            'title' => 'Parceiros',
            'visible' => $canViewCompetence,
            'items' => [
                [
                    'title' => 'Parceiros',
                    'route' => '/admin/partners',
                    'visible' => $canViewPartners,
                ],
                [
                    'title' => 'Competências',
                    'route' => '/admin/competences',
                    'visible' => $canViewCompetence,
                ],
            ],
        ],
        [
            'position' => 'center',
            'title' => 'Acesso',
            'visible' => $canViewUsers || $canViewAccessGroups,
            'items' => [
                [
                    'title' => 'Usuários',
                    'route' => '/admin/users',
                    'visible' => $canViewUsers,
                ],
                [
                    'title' => 'Grupos de Acesso',
                    'route' => '/admin/access-groups',
                    'visible' => $canViewAccessGroups,
                ],
                [
                    'title' => 'Permissões',
                    'route' => '/admin/permissions',
                    'visible' => $canViewPermission,
                ],
            ],
        ],
        [
            'position' => 'right',
            'title' => $user->name,
            'visible' => true,
            'custom_style' => 'right: 0px;left:unset;',
            'items' => [
                [
                    'title' => 'Sair',
                    'route' => '/login',
                    'visible' => true,
                ],
            ],
        ],
    ];
@endphp
<theme-navbar :items='@json($items)'>
    <theme-switcher></theme-switcher>
</theme-navbar>
