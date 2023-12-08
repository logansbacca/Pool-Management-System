<!DOCTYPE html>
<html>

<head>
    <title>Homepage</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-4 text-center">Seleziona il tipo di utente</h2>
        <form action="form_handler.php" method="POST">
            <div class="mb-4">
                <label for="ruolo" class="block text-gray-700">Ruolo:</label>
                <select name="ruolo" id="ruolo" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="cliente">Cliente</option>
                    <option value="istruttore">Istruttore</option>
                    <option value="responsabile">Responsabile</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="operazione" class="block text-gray-700">Operazione:</label>
                <select name="operazione" id="operazione" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="select">Select</option>
                    <option value="create">Create</option>
                    <option value="delete">Delete</option>
                    <option value="update">Update</option>
                </select>
            </div>
            <div class="text-center">
                <input type="submit" value="Submit" class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 cursor-pointer">
            </div>
        </form>
    </div>
</body>

</html>
