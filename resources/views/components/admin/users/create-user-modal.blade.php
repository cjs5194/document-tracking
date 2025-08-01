@props(['roles', 'user' => null, 'assignedRoles' => []])

@php
    $modalId = $user ? 'edit-user-modal-' . $user->id : 'create-user-modal';
    $formId = $user ? 'edit-user-form-' . $user->id : 'create-user-form';
@endphp

<div x-data="{ open: false }">
    <div>
        @if ($user)
            <button
                @click="open = true"
                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                Edit
            </button>
        @else
            <button
                @click="open = true"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white text-xs uppercase tracking-widest shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add New User
            </button>
        @endif
    </div>

    <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur-sm bg-white/30"
         @keydown.escape.window="open = false">
        <div @click.away="open = false" class="bg-white dark:bg-gray-800 rounded shadow-lg p-6 w-full max-w-xl mx-auto">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ $user ? 'Edit User' : 'Create User' }}</h2>

            <form id="{{ $formId }}" action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
                @csrf
                @if ($user)
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label for="name-{{ $modalId }}" class="block font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" id="name-{{ $modalId }}" name="name" value="{{ old('name', $user->name ?? '') }}"
                           class="w-full border rounded p-2" required>
                </div>

                <div class="mb-4">
                    <label for="email-{{ $modalId }}" class="block font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" id="email-{{ $modalId }}" name="email" value="{{ old('email', $user->email ?? '') }}"
                           class="w-full border rounded p-2" required>
                </div>

                <div class="mb-4">
                    <label for="password-{{ $modalId }}" class="block font-medium text-gray-700 dark:text-gray-300">
                        Password {{ $user ? '(leave blank to keep current)' : '' }}
                    </label>
                    <input type="password" id="password-{{ $modalId }}" name="password"
                           class="w-full border rounded p-2" {{ $user ? '' : 'required' }}>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation-{{ $modalId }}" class="block font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                    <input type="password" id="password_confirmation-{{ $modalId }}" name="password_confirmation"
                           class="w-full border rounded p-2" {{ $user ? '' : 'required' }}>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-300">Roles</label>
                    @foreach($roles as $role)
                        @php
                            $roleId = 'role_' . $role->id . '_' . ($user->id ?? 'new');
                        @endphp
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="{{ $roleId }}" name="roles[]" value="{{ $role->id }}"
                                {{ in_array($role->id, old('roles', $assignedRoles)) || (isset($user) && $user->hasRole($role->name)) ? 'checked' : '' }}
                                class="mr-2">
                            <label for="{{ $roleId }}" class="text-sm text-gray-900 dark:text-gray-100">{{ $role->name }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="open = false"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        {{ $user ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
