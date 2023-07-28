@php
    $user = Auth::user();
@endphp
@if ($user->plan_expires_at)
    @php
        $days = $user->plan_expires_at->diffInDays(now());
    @endphp
    @if ($days <= 15)
        <div class="bg-yellow-500 p-4 w-full shadow-md">
            <p class="text-white text-center">
                <span class="el-icon-warning"></span>
                Seu plano expira em <span class="font-bold">{{ $days }}</span>
                {{ $days > 1 ? 'Dias' : 'Dia' }}.<br>
                Clique <a href="/choose-a-plan" class="underline">aqui</a> para renovar.
            </p>
        </div>
    @endif
@endif
