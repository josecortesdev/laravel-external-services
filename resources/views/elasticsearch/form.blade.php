<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Forms</title>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                document.getElementById('index-form').addEventListener('submit', function (event) {
                    event.preventDefault();

                    fetch('/elasticsearch/index', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            id: document.querySelector('input[name="id"]').value,
                            title: document.querySelector('input[name="title"]').value,
                            content: document.querySelector('textarea[name="content"]').value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        showAlert('Document indexed successfully. Now you can search the document.', 'success');
                        document.getElementById('index-form').reset(); // Limpiar el formulario
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Failed to index document', 'danger');
                    });
                });

                document.getElementById('search-form').addEventListener('submit', function (event) {
                    event.preventDefault();

                    const query = document.querySelector('input[name="q"]').value;
                    console.log('Search query:', query);

                    fetch(`/elasticsearch/search?q=${query}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        console.log('Raw response:', response);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Parsed data:', data);
                        const resultsContainer = document.getElementById('search-results');
                        resultsContainer.innerHTML = '';

                        if (data.hits && data.hits.hits.length > 0) {
                            data.hits.hits.forEach(hit => {
                                console.log('Hit:', hit);
                                const resultItem = document.createElement('div');
                                resultItem.textContent = `Title: ${hit._source.title}, Content: ${hit._source.content}`;
                                resultsContainer.appendChild(resultItem);
                            });
                        } else {
                            resultsContainer.textContent = 'No results found';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });



                function showAlert(message, type) {
                    const alertContainer = document.getElementById('alert-container');
                    const alert = document.createElement('div');
                    alert.className = `alert alert-${type} alert-dismissible fade show`;
                    alert.role = 'alert';
                    alert.innerHTML = `
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;
                    alertContainer.appendChild(alert);
                    setTimeout(() => {
                        alert.classList.remove('show');
                        setTimeout(() => {
                            alert.remove();
                        }, 150);
                    }, 5000);
                }

                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('close')) {
                        const alert = event.target.closest('.alert');
                        alert.classList.remove('show');
                        setTimeout(() => {
                            alert.remove();
                        }, 150);
                    }
                });
            });
        </script>
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <main class="mt-6">
                        <div class="content">
                            <div id="alert-container"></div>
                            <div class="title m-b-md text-3xl font-bold text-center mb-8">
                                Form to test Elasticsearch
                            </div>

                            <form id="index-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id">
                                        ID
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4" type="text" name="id" placeholder="ID">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                                        Title
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4" type="text" name="title" placeholder="Title">
                                </div>
                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                                        Content
                                    </label>
                                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4" name="content" placeholder="Content"></textarea>
                                </div>
                                <div class="flex items-center justify-between">
                                    <button class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                        Index Document
                                    </button>
                                </div>
                            </form>

                            <form id="search-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="q">
                                        Search Document
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4" type="text" name="q" placeholder="Search">
                                </div>
                                <div class="flex items-center justify-between">
                                    <button class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                        Search
                                    </button>
                                </div>
                            </form>
                            <div id="search-results" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"></div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
