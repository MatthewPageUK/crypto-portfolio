<x-app-layout title=" - {{ __('Add a new token') }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload / Restore a Backup file') }}
        </h2>
    </x-slot>

    <x-form-card>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('backup.restore') }}" enctype="multipart/form-data">
            @csrf

            <p class="mb-5">Please select the backup file to restore. This will delete ALL data currently held and replace it with your backup. Please ensure this is what you want!</p>
            <!-- Symbol -->
            <div>
                <x-label for="backupfile" :value="__('Backup file')" />

                <input type="file"
                    id="backupfile" name="backupfile"
                    accept="text/plain"
                    class="w-full rounded-md shadow-sm border-gray-300 focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                    required
                >
            </div>

            <div class="flex items-center justify-end mt-12">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('dashboard') }}">
                    {{ __('Cancel') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Restore Backup') }}
                </x-button>
            </div>
        </form>
    </x-form-card>
</x-app-layout>
