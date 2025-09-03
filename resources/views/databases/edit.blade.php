<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <h1 class="my-6 text-2xl font-bold">Modifier la connexion</h1>

        <form method="POST" action="{{ route('databases.update', $database) }}" class="p-6 space-y-4 bg-white rounded-lg shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="mysql" {{ $database->type == 'mysql' ? 'selected' : '' }}>MySQL</option>
                    <option value="pgsql" {{ $database->type == 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Hôte</label>
                <input type="text" name="host" value="{{ $database->host }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nom de la base</label>
                <input type="text" name="dbname" value="{{ $database->dbname }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Utilisateur</label>
                <input type="text" name="username" value="{{ $database->username }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" value="{{ $database->password }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="flex justify-end">
                <a href="{{ route('databases.index') }}" class="mr-4 text-gray-600 hover:underline">Annuler</a>
                <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    ✅ Mettre à jour
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
