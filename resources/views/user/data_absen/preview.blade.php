<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Preview PDF</h1>
        <embed src="{{ route('admin.data_absen.preview-pdf', $id) }}" type="application/pdf" width="100%" height="600px">
        <div class="mt-4">
            <a href="{{ route('admin.data_absen.download-pdf', $id) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                Download PDF
            </a>
        </div>
    </div>
</body>
</html>