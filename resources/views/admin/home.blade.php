@php
    use App\Enums\DemandStatus;
@endphp
@extends('templates.admin')
@section('title', 'Página Inicial')
@section('breadcrumb')
    <vstack-breadcrumb :items="[{
        route: '/admin',
        title: 'Página Inicial'
    }, ]">
    </vstack-breadcrumb>
@endsection
@section('content')
    <dashboard-comp></dashboard-comp>
    <h3 class="text-3xl text-neutral-800 font-bold dark:text-neutral-200 mt-5">
        {{ SayGoodMorning() }}, {{ $user->firstName }}!
    </h3>
    <small class="text-neutral-800 font-bold dark:text-neutral-100 mt-4 mb-5">
        Atualmente temos <b>{{ $qtyDemands }}</b> {{ $qtyDemands > 1 ? 'demandas ativas' : 'demanda ativa' }}.
    </small>
    @php
        $resource = ResourcesHelpers::find('demands');
        $report_mode = false;
        $only_table = true;
        $extra_filters = ['status' => implode(',', [DemandStatus::open->name, DemandStatus::inprogress->name])];
    @endphp
    @if ($qtyDemands)
        {!! $resource->makeIndexContent(compact('resource', 'report_mode', 'only_table', 'extra_filters')) !!}
    @endif
@endsection
