<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="border-collapse border border-slate-500 ...">
                        <thead>
                            <tr>
                            <th class="border border-slate-600 ...">用戶 ID</th>
                            <th class="border border-slate-600 ...">帳號</th>
                            <th class="border border-slate-600 ...">存款金額</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                            <td class="border border-slate-700 ...">{{ $user->id }}</td>
                            <td class="border border-slate-700 ..."><a href={{ "/accounts/" . $user->id }}>{{ $user->name }}</a></td>
                            <td class="border border-slate-700 ...">100</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>