@props(['user', 'trigger' => true])

<div x-data="profileEditModal()" @open-profile-edit.window="open = true" class="w-full">
    <!-- Modal Trigger Button -->
    @if($trigger)
    <button type="button" @click="open = true" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none cursor-pointer w-full">
        <div class="max-w-40 truncate">{{ $user->name }}</div>
        <div class="ms-1">
            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </button>
    @endif

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 bg-black/50 z-50 flex items-end sm:items-center justify-center" @click.self="open = false">
        <div class="bg-white w-full sm:w-[500px] sm:rounded-2xl rounded-t-3xl shadow-xl overflow-hidden" @click.stop>
            <!-- Header -->
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Edit Profile</h3>
                <button type="button" @click="open = false" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <div class="p-4 sm:p-6 overflow-y-auto max-h-[60vh]">
                <form method="POST" action="{{ route('profile.update') }}" @submit="open = false">
                    @csrf
                    @method('PATCH')

                    <!-- Name Field -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @if ($errors->has('name'))
                            <p class="text-red-600 text-sm mt-1">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        @if ($errors->has('email'))
                            <p class="text-red-600 text-sm mt-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Success Message -->
                    @if (session('status') === 'profile-updated')
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-green-800 text-sm">Profile updated successfully!</p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="open = false" class="flex-1 px-4 py-2.5 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function profileEditModal() {
    return {
        open: false,
    }
}
</script>
