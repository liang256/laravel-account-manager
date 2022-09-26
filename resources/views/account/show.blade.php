<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="border-collapse border border-slate-500 ...">
                        <thead>
                            <tr>
                            <th class="border border-slate-600 ...">ID</th>
                            <th class="border border-slate-600 ...">金額</th>
                            <th class="border border-slate-600 ...">存款金額</th>
                            <th class="border border-slate-600 ...">日期</th>
                            <th class="border border-slate-600 ...">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $trans)
                            <tr>
                            <td class="border border-slate-700 ...">{{ $trans->id }}</td>
                            <td class="border border-slate-700 ...">{{ $trans->amount }}</td>
                            <td class="border border-slate-700 ...">{{ $trans->balance }}</td>
                            <td class="border border-slate-700 ...">{{ $trans->created_at }}</td>
                            <td class="border border-slate-700 ...">
                                @if ($userId == Auth::id())
                                <div class="flex">
                                    <a class="border border-gray-300 rounded-md" href="{{ route('transactions.edit', ['trans' => $trans->id]) }}">edit</a>
                                    <form action="{{ route('transactions.destroy', ['trans' => $trans->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border border-gray-300 rounded-md">delete</button>
                                    </form>
                                </div>
                                @endif
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($userId == Auth::id())
                    <a class="border border-gray-300 rounded-md px-4 mt-4" href="{{ route('transactions.create') }}">new transaction</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>