<x-layout>
    <main class="font-sans antialiased h-screen flex justify-center items-center flex-col">
        <div class="max-w-sm w-full">
            <h1 class="text-2xl mb-6">Login</h1>
            <form class="w-full">
                <input type="text" placeholder="Username" class="mb-4 input rounded border-solid border-2 py-2 px-4 w-full focus:border-emerald-800 outline-none" />
                <input type="password" placeholder="Password" class="mb-4 input rounded border-solid border-2 py-2 px-4 w-full focus:border-emerald-800 outline-none" />
                <div class="form-control">
                    <label class="label cursor-pointer">
                        <input type="checkbox" checked="checked" class="checkbox" />
                        <span class="label-text">Remember me</span>
                    </label>
                </div>
                <button class="w-full btn btn-wide btn-active bg-emerald-700 py-2 mt-4 rounded text-white hover:bg-emerald-800 transition ease-in-out delay-50">Log In</button>
            </form>
        </div>
    </main>
</x-layout>
