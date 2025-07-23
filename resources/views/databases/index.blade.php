<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center my-6">
            <h1 class="text-2xl font-bold">Connexions aux bases de données</h1>
            <div class="space-x-2">
        <a href="{{ route('backups.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            📜 Historique des sauvegardes
        </a>

        <a href="{{ route('databases.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ➕ Ajouter une connexion
        </a>
    </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hôte</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom BDD</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($databases as $db)
                        <tr>
                            <td class="px-6 py-4">{{ strtoupper($db->type) }}</td>
                            <td class="px-6 py-4">{{ $db->host }}</td>
                            <td class="px-6 py-4">{{ $db->dbname }}</td>
                            <td class="px-6 py-4">{{ $db->username }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <!-- Bouton sauvegarde maintenant -->
                                <form action="{{ route('databases.backup', $db) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        💾 Sauvegarder maintenant
                                    </button>
                                </form>

                                <!-- Bouton modifier -->
                                <a href="{{ route('databases.edit', $db) }}"
                                   class="text-blue-600 hover:text-blue-800">✏️ Modifier</a>

                                <!-- Bouton supprimer -->
                                <form action="{{ route('databases.destroy', $db) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Supprimer cette connexion ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">❌ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Aucune connexion enregistrée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
