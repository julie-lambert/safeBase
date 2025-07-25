<x-app-layout>
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="mb-6 text-3xl font-bold">
            👋 Bonjour {{ Auth::user()->name }}
        </h1>

        @if(Auth::user()->role === 'admin')
            <div class="p-4 mb-6 bg-blue-100 rounded shadow">
                <p class="font-semibold text-blue-800">
                    ✅ Vous êtes administrateur. Accédez au <a href="{{ route('admin.dashboard') }}" class="underline">tableau de bord Admin</a>.
                </p>
            </div>
        @else
            <div class="p-4 mb-6 bg-green-100 rounded shadow">
                <p class="text-green-800">
                    ✅ Vous êtes utilisateur simple. Vous avez un accès **lecture seule** aux sauvegardes.
                </p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @if(Auth::user()->role === 'admin')
                <!-- Lien vers admin dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="p-6 text-white bg-blue-600 rounded shadow hover:bg-blue-700">
                    🛠 Gestion Admin
                </a>
            @endif

            <!-- Lien vers sauvegardes -->
            <a href="{{ Auth::user()->role === 'admin' ? route('backups.index') : route('user.backups.index') }}"
               class="p-6 text-white bg-gray-600 rounded shadow hover:bg-gray-700">
                📜 Voir l’historique des sauvegardes
            </a>
        </div>
    </div>
</x-app-layout>
