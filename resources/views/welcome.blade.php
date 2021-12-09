<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta+Stencil">

    <style>
        .custom-font {
            font-family: "Allerta Stencil", Sans-serif;
        }
    </style>

    <title>Music App</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-700 text-white">
<div id="app" class="custom-font mb-48">
    <navbar></navbar>
    <app></app>

    <footer class="text-center w-full bg-gray-800 text-green-400 p-6 fixed mt-48 bottom-0 flex justify-around">
        <div>
            © all rights reserved
        </div>
        <div>
            2021
        </div>
        <div>
            Created by: Povilas Bačkierius
        </div>
    </footer>
</div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
</html>
