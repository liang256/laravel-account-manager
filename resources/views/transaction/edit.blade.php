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
                    <form action="{{ '/transactions/update/' . $trans->id }}" method="post">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Amount</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <input type="number" name="amount" id="amount" class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ $trans->amount }}" min="-9999999999.99" max="9999999999.99" step="0.01">
                            </div>
                        </div>
                        
                        <button class="border border-gray-300 rounded-md px-4 mt-4" type="submit">
                            update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>