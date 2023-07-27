@extends('templates.admin')
@section('title', 'Página Inicial')
@section('breadcrumb')
    <vstack-breadcrumb
        :items="[{
            route: '/admin',
            title: 'Página Inicial'
        }, {
            route: '/dashboard',
            title: 'Dashboard'
        }, ]">
    </vstack-breadcrumb>
@endsection
@section('content')
    <div class="flex my-4">
        <div class="w-full">
            <h1 class="text-5xl text-neutral-800 font-bold dark:text-neutral-200">Dashboard aqui ...</h1>
        </div>
    </div>
@endsection
