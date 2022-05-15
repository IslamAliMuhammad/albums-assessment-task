<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Albums') }}
        </h2>
    </x-slot>

    <div class="m-20 mt-0">
        <div class="mx-auto">
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-4 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <a type="button" href="{{ route('albums.create') }}"
                                class="mb-3 float-right inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">Create
                                Album</a>

                            <table class="min-w-full text-center">
                                <thead class="border-b bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-sm font-medium text-gray-900">
                                            #
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-sm font-medium text-gray-900">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-sm font-medium text-gray-900">
                                        </th>
                                    </tr>
                                </thead class="border-b">
                                <tbody>
                                    @foreach ($albums as $album)
                                    <tr class="bg-white border-b"">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900  whitespace-nowrap">
                                        {{ $album->id }}</td>
                                        <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                                            <a href="{{ route('albums.show', $album->id) }}">{{ $album->name }}</a>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                                            <div class="flex flex-row">
                                                <a type="button" href="{{ route('albums.show', $album->id) }}"
                                                    class="mr-2 inline-block px-6 py-2.5 bg-blue-400 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-500 hover:shadow-lg focus:bg-blue-500 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-600 active:shadow-lg transition duration-150 ease-in-out">Show</a>

                                                <a type="button" href="{{ route('albums.edit', $album->id) }}"
                                                    class="inline-block px-6 py-2.5 bg-purple-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-purple-800 active:shadow-lg transition duration-150 ease-in-out">Edit</a>

                                                <form action="{{ route('albums.destroy', $album->id) }}" method="POST">
                                                    @csrf

                                                    <button type="submit"
                                                        class="ml-2 inline-block px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg transition duration-150 ease-in-out">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                {{ $albums->onEachSide(5)->links() }}
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal HTML embedded directly into document -->
    <div id="askUser" class="modal">
        <div class="">
            <div class="flex flex-row w-full mb-3">
                <button type="button" id="deleteAllBtn"
                    class="inline-block px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg transition duration-150 ease-in-out">delete
                    all the pictures in the album</button>
                <button type="button" id="moveBtn"
                    class="ml-3 inline-block px-6 py-2.5 bg-yellow-500 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-yellow-600 hover:shadow-lg focus:bg-yellow-600 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-yellow-700 active:shadow-lg transition duration-150 ease-in-out">move
                    the pictures to another album</button>
            </div>

            <div id="albumSearch" hidden>
                <input type="text" id="albumNameInp" name="albumName" class="
                    form-control
                    px-3
                    py-1.5
                    text-base
                    font-normal
                    text-gray-700
                    bg-white bg-clip-padding
                    border border-solid border-gray-300
                    rounded
                    transition
                    ease-in-out
                    m-0
                    focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                    id="exampleFormControlInput1" placeholder="Enter Album Name" />

                <button type="button" id="confirmMoveBtn"
                    class="inline-block px-6 py-2.5 bg-green-500 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-green-600 hover:shadow-lg focus:bg-green-600 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-700 active:shadow-lg transition duration-150 ease-in-out">Move</button>
            </div>
        </div>
    </div>

    <!-- Link to open the modal -->
    <p><a id="openModal" href="#askUser" rel="modal:open" hidden>Open Modal</a></p>

    @section('scripts')

    <!-- Remember to include jQuery :) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

    <script>
        $("form").submit(function(e){
                e.preventDefault();
                $.ajax(e.target.action, {
                    type: 'DELETE',
                    headers: {
                        "X-CSRF-TOKEN": '{{ csrf_token() }}'
                    },
                    success: function (data, status, xhr) {
                        if(data == true) {
                            location.reload();
                        }else {
                            $('#openModal').click();

                            var albumId = e.target.action.substr(e.target.action.lastIndexOf('/') + 1);

                           $('#deleteAllBtn').click(function (e) {

                                $.ajax({
                                    type: "POST",
                                    url: `albums/${albumId}/delete-all`,
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    success: function(data, status, xhr) {
                                        location.reload();
                                    },
                                    error: function (qXhr, textStatus, errorMessage) {
                                        console.log(errorMessage);

                                    },
                                });

                            });

                            $('#moveBtn').click(function (e) {


                                $('#albumSearch').removeAttr('hidden');

                                var albumName;

                                $("#albumNameInp").on("keyup", function() {
                                    albumName = $(this).val();
                                });

                                $('#confirmMoveBtn').click(function (e) {
                                    $.ajax({
                                        type: "POST",
                                        url: `albums/${albumId}/move-pictures`,
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        data: {
                                            albumName: albumName,
                                        },
                                        success: function(data, status, xhr) {
                                            if(data == true) {
                                                location.reload();
                                            } else {

                                            }
                                        },
                                        error: function (qXhr, textStatus, errorMessage) {
                                            console.log(errorMessage);
                                        },
                                    });
                                });
                            });

                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                            console.log(errorMessage);

                    }
                });
            });
    </script>
    @endsection

</x-app-layout>
