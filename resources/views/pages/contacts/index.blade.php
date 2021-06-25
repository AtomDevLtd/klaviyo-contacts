<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="flex justify-start items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ __('Contacts') }}
                </h2>
            </div>
            <div class="flex justify-between items-center">
                <h4 class="font-light  italic text-xl text-gray-800">
                    {{ $contactList->name }}
                </h4>
            </div>
            <div class="flex justify-end">
                <form action="{{ route('contacts-import', $contactList) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <span>Upload a file</span>
                        <input type="file" name="file">
                    </label>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mx-2 py-2 px-4 border border-blue-700 rounded">Import contacts</button>
                </form>
                <a href="{{ route('contactLists.contacts.create', $contactList) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
                    Create Contact
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table id="contacts-table" class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Phone
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Synced In Klaviyo</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($contacts as $contact)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="bg-gray-400 rounded-full h-10 w-10 flex items-center justify-center">{{ Str::limit($contact->first_name, 1, '') }}</div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm text-gray-900">{{ $contact->first_name . ' ' . $contact->last_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $contact->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $contact->phone }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('contactLists.contacts.edit', [$contactList, $contact->id]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($contact->klaviyo_id)
                                                    <button data-synced="{{$contact->id}}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 synced">
                                                        Synced
                                                    </button>
                                                @else
                                                    <button class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-300 text-red--800 not-synced">
                                                        Not synced
                                                    </button>
                                                    <button  data-sync-with-klaviyo-id="{{$contact->id}}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-200 text-red-600 try-sync">
                                                        Try
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('page-scripts')
        <script>
            $(document).ready(function(){
                $('body').on('click', '.try-sync', function(e) {

                    e.preventDefault();

                    let contact = $(this).data('sync-with-klaviyo-id');

                    if (contact) {

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });


                        $.ajax({
                            method: 'post',
                            url: '/contacts/sync/' + contact,
                            data: {
                                _method: 'PUT',
                            },
                            success: function (response) {
                                if(response){
                                    $('#contacts-table').find('[data-sync-with-klaviyo-id="' + response.data.id + '"]').remove();
                                }
                            },
                            error: function (jqXHR) {

                                console.log('error');

                            }
                        });

                    }

                });
            });

        </script>
    @endpush
</x-app-layout>

