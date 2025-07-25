<x-app-layout>
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="my-6 text-2xl font-bold">📜 Historique des sauvegardes (lecture seule)</h1>

        <div class="overflow-hidden bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Base</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-xs font-medium text-right text-gray-500 uppercase">Télécharger</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($backups as $backup)
                        <tr>
                            <td class="px-6 py-4">{{ $backup->database->dbname }}</td>
                            <td class="px-6 py-4">{{ $backup->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                @if($backup->status === 'success')
                                    <span class="font-semibold text-green-600">✅ Succès</span>
                                @else
                                    <span class="font-semibold text-red-600">❌ Échec</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($backup->status === 'success')
                                    <a href="{{ route('user.backups.download', $backup) }}"
                                       class="px-3 py-1 text-white bg-blue-500 rounded hover:bg-blue-600">
                                       ⬇ Télécharger
                                    </a>
                                @else
                                    <span class="text-gray-400">Indisponible</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Aucune sauvegarde disponible
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
