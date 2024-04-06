<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    @vite('resources/css/app.css')
</head>

<body class="font-sans antialiased h-screen flex justify-center items-center flex-col">
    <main class="max-w-sm w-full">
        <h1 class="text-2xl mb-6">Login</h1>
        <form class="w-full">
            <input type="text" placeholder="Username" class="mb-4 input rounded border-solid border-2 py-2 px-4 w-full focus:border-emerald-800 outline-none" />
            <input type="password" placeholder="Password" class="input rounded border-solid border-2 py-2 px-4 w-full focus:border-emerald-800 outline-none" />
            <button class="w-full btn btn-wide btn-active bg-emerald-800 py-2 mt-4 rounded text-white">Log In</button>
        </form>
    </main>
</body>

</html>
