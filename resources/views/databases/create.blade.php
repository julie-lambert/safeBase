<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold my-6">Ajouter une connexion</h1>

        <form method="POST" action="{{ route('databases.store') }}" class="bg-white p-6 rounded-lg shadow space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="mysql">MySQL</option>
                    <option value="pgsql">PostgreSQL</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Hôte</label>
                <input type="text" name="host" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nom de la base</label>
                <input type="text" name="dbname" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Utilisateur</label>
                <input type="text" name="username" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="flex justify-end">
                <a href="{{ route('databases.index') }}" class="mr-4 text-gray-600 hover:underline">Annuler</a>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    ✅ Enregistrer
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
