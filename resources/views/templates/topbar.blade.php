@php
    $user = Auth::user();
    $canViewUsers = $user->hasPermissionTo('viewlist-users');
    $canViewAccessGroups = $user->hasPermissionTo('viewlist-access-groups');
    $canViewPermission = $user->hasPermissionTo('viewlist-permissions');
    $canViewCompetence = $user->hasPermissionTo('viewlist-competence');
    $canViewCustomers = $user->hasPermissionTo('viewlist-customers');
    $canViewProjects = $user->hasPermissionTo('viewlist-projects');
    $canViewPartners = $user->hasPermissionTo('viewlist-partners');
    $canViewDemands = $user->hasPermissionTo('viewlist-demands');
    $canViewSquads = $user->hasPermissionTo('viewlist-squads');
    $canViewTransactions = $user->hasPermissionTo('viewlist-transactions');
    
    $items = [
        [
            'position' => 'center',
            'title' => 'Financeiro',
            'route' => '/admin/financial',
            'visible' => $canViewTransactions,
            'items' => [],
        ],
        [
            'position' => 'center',
            'title' => 'Projetos',
            'visible' => $canViewProjects || $canViewDemands,
            'items' => [
                [
                    'position' => 'center',
                    'title' => 'Clientes',
                    'route' => '/admin/customers',
                    'visible' => $canViewCustomers,
                    'items' => [],
                ],
                [
                    'position' => 'center',
                    'title' => 'Projetos',
                    'route' => '/admin/projects',
                    'visible' => $canViewProjects,
                    'items' => [],
                ],
                [
                    'position' => 'center',
                    'title' => 'Demandas',
                    'route' => '/admin/demands',
                    'visible' => $canViewDemands,
                    'items' => [],
                ],
            ],
        ],
        [
            'position' => 'center',
            'title' => 'Parceiros',
            'visible' => $canViewCompetence || $canViewPartners || $canViewSquads,
            'items' => [
                [
                    'title' => 'Parceiros',
                    'route' => '/admin/partners',
                    'visible' => $canViewPartners,
                ],
                [
                    'title' => 'Squads',
                    'route' => '/admin/squads',
                    'visible' => $canViewSquads,
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
