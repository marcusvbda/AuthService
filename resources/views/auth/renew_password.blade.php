@extends('templates.auth')
@section('title', 'Renovação de Senha')
@section('form')
@section('image', '/assets/images/auth/forgot_password.png')
<auth-renew token="{{ $tokenValue }}">
</auth-renew>
@endsection
