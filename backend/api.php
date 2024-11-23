<?php
// Дозволяє доступ до ресурсів з вашого фронтенду
header("Access-Control-Allow-Origin: http://cases2.ct.ws"); // Вказати правильний домен
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// Якщо це запит типу OPTIONS (попередній запит), просто відповідаємо статусом 200
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Налаштування підключення до бази даних на InfinityFree
$host = 'sql205.infinityfree.com';
$dbname = 'if0_37682234_case_cs2';
$username = 'if0_37682234';
$password = 'Z2GJ6lzY2mlg';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Обробка запитів
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        // Реєстрація нового користувача
        if (isset($input['account_name'], $input['email'], $input['password'])) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE name = ?");
            $stmt->execute([$input['account_name']]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'error' => 'Акаунт з такою назвою вже існує.']);
                exit;
            }

            $account_name = $input['account_name'];
            $email = $input['email'];
            $password = password_hash($input['password'], PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO accounts (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$account_name, $email, $password]);

            echo json_encode(['success' => true, 'message' => 'Аккаунт успішно додано.']);
        }

        // Логін існуючого користувача
        elseif (isset($input['email'], $input['password'])) {
            $stmt = $pdo->prepare("SELECT * FROM accounts WHERE email = ?");
            $stmt->execute([$input['email']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($input['password'], $user['password'])) {
                echo json_encode(['success' => true, 'message' => 'Логін успішний']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Невірний email або пароль']);
            }
        }

        // Додавання кейсу з урахуванням кількості та загальної ціни
        elseif (isset($input['account_id'], $input['case_name'], $input['price'], $input['quantity'])) {
            $account_id = $input['account_id'];
            $case_name = $input['case_name'];
            $price = $input['price'];
            $quantity = $input['quantity'];
            $total_price = $price * $quantity;
            $date_added = date('Y-m-d');

            $stmt = $pdo->prepare("SELECT name FROM accounts WHERE id = ?");
            $stmt->execute([$account_id]);
            $account_name = $stmt->fetchColumn();

            if (!$account_name) {
                echo json_encode(['success' => false, 'error' => 'Невірний ID акаунту.']);
                exit;
            }

            $photoPaths = [
                'Футляр «Сновидіння та кошмари»' => 'case-cs2/src/assets/grozy.png',
                'Футляр «Кіловат»' => 'case-cs2/src/assets/kilovat.png',
                'Футляр «Революція»' => 'case-cs2/src/assets/revolution.png',
                'Футляр «Перелом»' => 'case-cs2/src/assets/perelom.png',
                'Футляр «Віддача»' => 'case-cs2/src/assets/viddacha.png',
                'Футляр «Укус змії»' => 'case-cs2/src/assets/kyskys.png'
            ];

            if (!isset($photoPaths[$case_name])) {
                echo json_encode(['success' => false, 'error' => 'Невідома назва кейсу.']);
                exit;
            }

            $photoPath = $photoPaths[$case_name];

            $stmt = $pdo->prepare("INSERT INTO cases (account_id, account_name, case_name, price, quantity, total_price, date_added, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$account_id, $account_name, $case_name, $price, $quantity, $total_price, $date_added, $photoPath]);

            echo json_encode(['success' => true, 'message' => 'Кейс успішно додано.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Некоректні дані для запиту.']);
        }
    }

    // Отримання акаунтів або кейсів
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['fetch']) && $_GET['fetch'] === 'accounts') {
            $stmt = $pdo->query("SELECT * FROM accounts");
            $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'accounts' => $accounts]);
        } else {
            $stmt = $pdo->query("SELECT * FROM cases");
            $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'cases' => $cases]);
        }
    }

    // Видалення акаунту і пов'язаних кейсів
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $id = $data['id'];

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE id = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() == 0) {
                echo json_encode(['success' => false, 'error' => 'Акаунт не знайдений.']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM cases WHERE account_id = ?");
            $stmt->execute([$id]);

            $stmt = $pdo->prepare("DELETE FROM accounts WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true, 'message' => 'Акаунт успішно видалено.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Не вказано ID акаунту для видалення.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Помилка: ' . $e->getMessage()]);
}
?>
