<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center my-6">
            <h1 class="text-2xl font-bold">Historique des sauvegardes</h1>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Base</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fichier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($backups as $backup)
                        <tr>
                            <td class="px-6 py-4">
                                {{ $backup->database->dbname }} ({{ strtoupper($backup->database->type) }})
                            </td>
                            <td class="px-6 py-4">
                                {{ basename($backup->file_path) }}
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
                            <td class="px-6 py-4 text-right">
                                @if($backup->status === 'success')
                                    <a href="{{ route('backups.download', $backup) }}"
                                       class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                       ⬇ Télécharger
                                    </a>
                                                        <!-- Restaurer -->
                                    <form action="{{ route('backups.restore', $backup) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('⚠ Restaurer cette sauvegarde écrasera la base actuelle. Continuer ?')">
                                        @csrf
                                        <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                            🔄 Restaurer
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">Impossible</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Aucune sauvegarde pour le moment
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $backups->links() }}
        </div>
    </div>
</x-app-layout>
