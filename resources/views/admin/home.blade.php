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
    <div class="flex mb-4 mt-8">
        <div class="w-full">
            <h1 class="text-5xl text-neutral-800 font-bold dark:text-neutral-200">Dashboard</h1>
        </div>
    </div>
    <dashboard-comp></dashboard-comp>
@endsection
