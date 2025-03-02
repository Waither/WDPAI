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

        h1 {
            color: #333;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input, select, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        h2 {
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Kalkulator</h1>
    <form method="post">
        <input type="number" name="num1" placeholder="Pierwsza liczba" required>
        <select name="operation">
            <option value="add">Dodaj</option>
            <option value="subtract">Odejmij</option>
            <option value="multiply">Pomnóż</option>
            <option value="divide">Podziel</option>
        </select>
        <input type="number" name="num2" placeholder="Druga liczba" required>
        <button type="submit">Oblicz</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $num1 = $_POST['num1'];
        $num2 = $_POST['num2'];
        $operation = $_POST['operation'];
        $result = 0;

        switch ($operation) {
            case "add":
                $result = $num1 + $num2;
                break;
            case "subtract":
                $result = $num1 - $num2;
                break;
            case "multiply":
                $result = $num1 * $num2;
                break;
            case "divide":
                if ($num2 != 0) {
                    $result = $num1 / $num2;
                } else {
                    echo "Nie można dzielić przez zero!";
                    exit;
                }
                break;
            default:
                echo "Nieznana operacja";
                exit;
        }

        echo "<h2>Wynik: $result</h2>";
    }
    ?>
</body>
</html>