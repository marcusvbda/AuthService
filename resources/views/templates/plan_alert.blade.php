@php
    $user = Auth::user();
@endphp
@if ($user->plan === 'test')
    @php
        $days = $user->plan_expires_at && $user->plan_expires_at->diffInDays(now());
    @endphp
    <div class="bg-yellow-500 p-4 w-full shadow-md">
        <p class="text-white text-center">
            <span class="el-icon-warning"></span>
            Seu plano expira em <span class="font-bold">{{ $days }}</span> {{ $days > 1 ? 'Dias' : 'Dia' }}.
        </p>
    </div>
@endif
