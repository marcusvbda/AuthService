@php
    $withoutImage = true;
    $user = Auth::user();
    $plan = $user->plan;
    $expired = $user->planIsExpired();
@endphp

@extends('templates.auth')
@section('title', 'Selecione um plano')
@section('form')
    <auth-plan plan="{{ $plan }}" :expired='@json($expired)'>
    </auth-plan>
@endsection
