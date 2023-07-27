@php
    $user = Auth::user();
    $items = [
        [
            'position' => 'center',
            'title' => 'Dashboard',
            'route' => '/admin/dashboard',
            'visible' => true,
            'items' => [],
        ],
        // [
        //     'position' => 'center',
        //     'title' => 'Relatórios',
        //     'visible' => $canViewReportLeads,
        //     'items' => [
        //         [
        //             'title' => 'Relatório de Leads',
        //             'route' => '/admin/relatorios/leads',
        //             'visible' => $canViewReportLeads,
        //         ],
        //     ],
        // ],
        [
            'position' => 'right',
            'title' => $user->email,
            'visible' => true,
            'custom_style' => 'left: -100px;',
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
