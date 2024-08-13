<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Backlog') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-12">
        <div class="flex justify-end mb-4">
            <a href="{{ route('users.create') }}" class="btn btn-primary">Create New User</a>
        </div>

        <!-- Display list of users -->
        <div class="overflow-x-auto mt-4">
            <table class="table text-base">
                <thead class="text-sm">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="hover group">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->is_admin ? 'Admin' : 'Assistant' }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline btn-warning">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline btn-error" onclick="return confirm('Are you sure?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
