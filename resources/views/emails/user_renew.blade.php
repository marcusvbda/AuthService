@extends('templates.mail')
@section('title', 'Ativação de Usuário')
@section('content')
    <p>Olá {{ $firstName }},</p>
    <p>Foi solicitado uma renovação de senha para este usuário! Para renovar suas credenciais, clique no botão abaixo:</p>
    <p style="text-align: center;">
        <a href="{{ $renewLink }}" target="_blank"
            style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px;">
            renovar senha
        </a>
    </p>
    <p>Se o botão não funcionar, você também pode copiar e colar o seguinte link em seu navegador:</p>
    <p>{{ $renewLink }}</p>
    <p>Após a renovação, você terá acesso ao sistema com suas novas credenciais imediatamente.</p>
    <p>Se você não solicitou esta renovação, por favor, ignore este e-mail.</p>
    <p>Este email tem validade de 24 horas.</p>
    <p>Obrigado</p>
@endsection
