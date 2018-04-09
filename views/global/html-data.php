<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Your data</title>

    <style type="text/css">
        table {
            font-family: verdana,arial,sans-serif;
            font-size:11px;
            color:#333333;
            border-width: 1px;
            border-color: #3A3A3A;
            border-collapse: collapse;
        }
        table th {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #3A3A3A;
            background-color: #B3B3B3;
        }
        table td {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #3A3A3A;
            background-color: #ffffff;
        }

        td.key {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?= $table; ?>
</body>
</html>