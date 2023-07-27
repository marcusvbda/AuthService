@extends('templates.default')
@section('title', 'Login')
@section('body')
    <main class="h-full flex flex-col md:flex-row">
        @if (!@$withoutImage)
            <div class="w-full h-full px-8 hidden md:block md:w-1/2">
                <div class="flex items-center justify-center h-full">
                    <img src="@yield('image')" class="max-w-md" />
                </div>
            </div>
        @endif
        <div
            class="bg-white w-full h-full order-2 p-4 flex items-center justify-center dark:bg-gray-800 pb-8 @if (!@$withoutImage) md:w-1/2  @else md:w-full @endif">
            @yield('form')
        </div>
    </main>
@endsection
