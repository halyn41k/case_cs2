<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'case_cs2';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['account_name'], $input['email'], $input['password'])) {
            // Check if account already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE name = ?");
            $stmt->execute([$input['account_name']]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'error' => 'Акаунт з такою назвою вже існує.']);
                exit;
            }

            // Handle account addition with email and password
            $account_name = $input['account_name'];
            $email = $input['email'];
            $password = $input['password']; // Store the password in plaintext

            $stmt = $pdo->prepare("INSERT INTO accounts (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$account_name, $email, $password]);
            
            echo json_encode(['success' => true, 'message' => 'Аккаунт успішно додано.']);
        } elseif (isset($_POST['data'])) {
            // Handle case addition
            $data = json_decode($_POST['data'], true);
            if (isset($data['account_id']) && isset($data['case_name']) && isset($data['price'])) {
                $account_id = $data['account_id'];
                $case_name = $data['case_name'];
                $price = $data['price'];
                $date_added = date('Y-m-d');

                // Get account name from account_id
                $stmt = $pdo->prepare("SELECT name FROM accounts WHERE id = ?");
                $stmt->execute([$account_id]);
                $account_name = $stmt->fetchColumn();

                // Set photo path based on case name
                $photoPath = '';
                switch ($case_name) {
                    case 'Футляр «Сновидіння та кошмари»':
                        $photoPath = 'case-cs2/src/assets/grozy.png';
                        break;
                    case 'Футляр «Кіловат»':
                        $photoPath = 'case-cs2/src/assets/kilovat.png';
                        break;
                    case 'Футляр «Революція»':
                        $photoPath = 'case-cs2/src/assets/revolution.png';
                        break;
                    case 'Футляр «Перелом»':
                        $photoPath = 'case-cs2/src/assets/perelom.png';
                        break;
                    case 'Футляр «Віддача»':
                        $photoPath = 'case-cs2/src/assets/viddacha.png';
                        break;
                    case 'Футляр «Укус змії»':
                        $photoPath = 'case-cs2/src/assets/kyskys.png';
                        break;
                    default:
                        echo json_encode(['success' => false, 'error' => 'Невідома назва кейсу.']);
                        exit;
                }

                $stmt = $pdo->prepare("INSERT INTO cases (account_id, account_name, case_name, price, date_added, photo) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$account_id, $account_name, $case_name, $price, $date_added, $photoPath]);
                echo json_encode(['success' => true, 'message' => 'Кейс успішно додано.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Некоректні дані.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Некоректний запит.']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['fetch']) && $_GET['fetch'] === 'accounts') {
            $stmt = $pdo->query("SELECT * FROM accounts");
            $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'accounts' => $accounts]);
        } else {
            $stmt = $pdo->query("SELECT * FROM cases");
            $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'cases' => $cases]);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $id = $data['id'];

            $stmt = $pdo->prepare("DELETE FROM cases WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true, 'message' => 'Кейс успішно видалено.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Некоректні дані для видалення кейсу.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Помилка: ' . $e->getMessage()]);
}
?>
