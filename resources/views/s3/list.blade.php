<!DOCTYPE html>
<html>
<head>
    <title>List Files in S3</title>
</head>
<body>
    <h1>Files in S3</h1>
    <ul>
        @foreach ($files as $file)
            <li><a href="{{ Storage::disk('s3')->url($file) }}" target="_blank">{{ $file }}</a></li>
        @endforeach
    </ul>
</body>
</html>
