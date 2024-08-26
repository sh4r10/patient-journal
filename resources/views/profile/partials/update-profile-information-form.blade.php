<section>
    <header>
        <h2 class="text-lg font-medium text-slate-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __("An overview of your information.") }}
        </p>
    </header>

    <form class="mt-6 space-y-6 pb-4">
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input type="text" disabled="true" class="mt-1 block w-full bg-slate-100" :value="$user->name" required autofocus autocomplete="name" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input type="email" disabled="true" class="mt-1 block w-full bg-slate-100" :value="$user->email" />
        </div>
    </form>
</section>
