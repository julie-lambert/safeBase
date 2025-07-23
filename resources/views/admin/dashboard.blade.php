<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold my-6">📊 Tableau de bord Admin</h1>

        <!-- Stats rapides -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-600">Connexions enregistrées</h2>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $connectionsCount }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-600">Sauvegardes totales</h2>
                <p class="text-4xl font-bold text-green-600 mt-2">{{ $backupsCount }}</p>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="flex gap-4 mb-6">
            <a href="{{ route('databases.index') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                💾 Gérer les connexions
            </a>
            <a href="{{ route('backups.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                📜 Historique des sauvegardes
            </a>
        </div>

        <!-- Sauvegardes récentes -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold">⏳ 5 dernières sauvegardes</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Base</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentBackups as $backup)
                        <tr>
                            <td class="px-6 py-4">
                                {{ $backup->database->dbname }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $backup->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($backup->status === 'success')
                                    <span class="text-green-600 font-semibold">✅ Succès</span>
                                @else
                                    <span class="text-red-600 font-semibold">❌ Échec</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                Aucune sauvegarde récente
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
