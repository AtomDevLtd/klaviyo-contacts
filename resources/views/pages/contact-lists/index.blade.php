<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="flex justify-start items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ __('Contact lists') }}
                </h2>
            </div>
            <div class="flex justify-between items-center">
                <form action="{{ route('trackActivity') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded">
                        Track Activity
                    </button>
                </form>
            </div>
            <div class="flex justify-end">
                <a href="{{ route('contactLists.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
                    Create List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col">
                    <x-success-message/>
                    <x-validation-errors/>
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table id="contact-lists-table" class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contacts
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Edit
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            In Klaviyo
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($contactLists as $list)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $list->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                    <a  href="{{ route('contactLists.contacts.index', $list->id) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                      Show List
                                                    </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('contactLists.edit', $list->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if(auth()->user()->hasKlaviyoApiKeys())
                                                    @if($list->klaviyo_id)
                                                        <button data-synced="{{$list->id}}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 synced">
                                                            Synced
                                                        </button>
                                                    @else
                                                        <button class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-300 text-red--800 not-synced">
                                                            Not synced
                                                        </button>
                                                        <button  data-sync-with-klaviyo-id="{{$list->id}}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-200 text-red-600 try-sync">
                                                            Try
                                                        </button>
                                                    @endif

                                                @else
                                                    <p>No API keys set</p>
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

                    let contactList = $(this).data('sync-with-klaviyo-id');

                    if (contactList) {

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });


                        $.ajax({
                            method: 'post',
                            url: '/contactLists/sync/' + contactList,
                            data: {
                                _method: 'PUT',
                            },
                            success: function (response) {
                                if(response){
                                    $('#contact-lists-table').find('[data-sync-with-klaviyo-id="' + response.data.id + '"]').remove();
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
