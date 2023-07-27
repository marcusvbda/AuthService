@php
    $user = Auth::user();
    $canViewUsers = $user->hasPermissionTo('viewlist-users');
    $canViewAccessGroups = $user->hasPermissionTo('viewlist-access-groups');
    $canViewPermission = $user->hasPermissionTo('viewlist-permissions');
    
    $items = [
        [
            'position' => 'center',
            'title' => 'Dashboard',
            'route' => '/admin/dashboard',
            'visible' => true,
            'items' => [],
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
            'title' => $user->email,
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
