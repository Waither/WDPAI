<!DOCTYPE html>
<html>
<head>
    <title>Kalkulator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .calculator {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .display {
            width: calc(100% - 20px);
            margin: 10px auto;
            padding: 10px;
            font-size: 24px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: right;
        }

        .buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .buttons button {
            padding: 20px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f4f4f4;
            cursor: pointer;
        }

        .buttons button:hover {
            background-color: #ddd;
        }

        .buttons .equals {
            grid-column: span 4;
            background-color: #28a745;
            color: white;
        }

        .buttons .equals:hover {
            background-color: #218838;
        }
    </style>
</head>
<body style="height: 100%;">
    <script>
        console.log(<?= preg_match('/Mobile|Android|iPhone|iPad/i', $userAgent ?? $_SERVER['HTTP_USER_AGENT']) ? 'true' : 'false' ?> ? 'Użytkownik korzysta z urządzenia mobilnego.' : 'Użytkownik korzysta z komputera.');
    </script>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Key</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_ENV as $key => $value): ?>
                <tr>
                    <td><?php echo htmlspecialchars($key); ?></td>
                    <td>
                        <?php 
                            if (is_array($value)) {
                                echo '<pre>' . htmlspecialchars(print_r($value, true)) . '</pre>';
                            } else {
                                echo htmlspecialchars($value);
                            }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>`