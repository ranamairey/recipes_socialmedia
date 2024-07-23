<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YAM</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f1f1f1;
        }

        h1 {
            text-align: center;
            color: blue;
        }

        h2 {
            text-align: center;
            color: red;
        }

        p {
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>
    <h1>Advertisement</h1>

    <h2>Description</h2>
    <p>{{ $advertisement->description }}</p>

    <h2>Attachment</h2>
    <p>Attached file: {{ $fileName }}</p>
</body>
</html>