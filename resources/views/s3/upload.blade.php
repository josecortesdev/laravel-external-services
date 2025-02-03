<!DOCTYPE html>
<html>
<head>
    <title>Upload File to S3</title>
</head>
<body>
    @if (session('success'))
        <div>
            {{ session('success') }}
            <a href="{{ session('file_url') }}" target="_blank">View File</a>
        </div>
    @endif

    <form action="/s3/upload" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <button type="submit">Upload</button>
    </form>
</body>
</html>
