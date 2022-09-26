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
                            <th class="border border-slate-600 ...">金額</th>
                            <th class="border border-slate-600 ...">存款金額</th>
                            <th class="border border-slate-600 ...">日期</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $trans)
                            <tr>
                            <td class="border border-slate-700 ...">{{ $trans->amount }}</td>
                            <td class="border border-slate-700 ...">{{ $trans->balance }}</td>
                            <td class="border border-slate-700 ...">{{ $trans->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <a class="border border-gray-300 rounded-md px-4 mt-4" href={{ route("transactions.create") }}>new transaction</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>