<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ config('app.name', 'School ERP') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f7fb;
            color: #2d3748;
        }

        .panel {
            background: #fff;
            padding: 32px 40px;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(31, 45, 61, 0.08);
            text-align: center;
        }

        form {
            margin-top: 16px;
        }

        button {
            border: none;
            background: #5bb5e8;
            color: #fff;
            padding: 10px 18px;
            border-radius: 999px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="panel">
        <h1>Welcome to {{ config('app.name', 'School ERP') }}</h1>
        <p>You are signed in successfully.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Sign Out</button>
        </form>
    </div>
</body>
</html>
