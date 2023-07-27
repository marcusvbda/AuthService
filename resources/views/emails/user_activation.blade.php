@extends('templates.mail')
@section('title', 'Ativação de Usuário')
@section('content')
    <p>Olá {{ $firstName }},</p>
    <p>Seu cadastro foi realizado com sucesso! Para ativar sua conta, clique no botão abaixo:</p>
    <p style="text-align: center;">
        <a href="{{ $activationLink }}" target="_blank"
            style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px;">
            Ativar conta
        </a>
    </p>
    <p>Se o botão não funcionar, você também pode copiar e colar o seguinte link em seu navegador:</p>
    <p>{{ $activationLink }}</p>
    <p>Após a ativação, você poderá acessar todos os recursos liberados para a role de seu usuário.</p>
    <p>Se você não criou esta conta, por favor, ignore este e-mail.</p>
    <p>Este email tem validade de 24 horas, caso seu usuário não for ativado o mesmo será desativado após este período.</p>
    <p>Obrigado</p>
@endsection
