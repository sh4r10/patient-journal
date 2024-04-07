<x-layout>
    <main class="font-sans antialiased h-screen flex justify-center items-center flex-col">
        <div class="max-w-md w-full">
            <h1 class="text-2xl mb-4">Login</h1>
            <form method="post" action={{route('users.login')}} class="w-full">
            @csrf    
            <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Username</span>
                    </div>
                    <input type="text" name="username" placeholder="admin" class="input input-primary input-bordered w-full" />
                </label>
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Password</span>
                    </div>
                    <input type="password" name="password" placeholder="xxxxxxx" class="input input-primary input-bordered w-full" />
                </label>
                <div class="form-control my-2">
                    <label class="label cursor-pointer flex justify-start items-center gap-2">
                        <input type="checkbox" checked="checked" class="checkbox checkbox-primary" />
                        <span class="label-text">Remember me</span>
                    </label>
                </div>
                <button class="w-full btn btn-wide btn-accent">Log In</button>
            </form>
        </div>
    </main>
</x-layout>
