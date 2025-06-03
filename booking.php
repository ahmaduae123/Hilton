<?php
require 'db.php';

$hotel_id = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : 0;
$checkIn = isset($_GET['checkIn']) ? $_GET['checkIn'] : '';
$checkOut = isset($_GET['checkOut']) ? $_GET['checkOut'] : '';

if ($hotel_id) {
    $stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
    $stmt->execute([$hotel_id]);
    $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $total_price = $_POST['total_price'];

    $stmt = $pdo->prepare("INSERT INTO bookings (hotel_id, user_name, user_email, check_in, check_out, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$hotel_id, $user_name, $user_email, $check_in, $check_out, $total_price]);

    $confirmation_message = "Booking confirmed for $user_name at {$hotel['name']} from $check_in to $check_out. Total: $$total_price.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room - Hilton Clone</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f5f6fa;
        }

        header {
            background: #003087;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .booking-form {
            max-width: 600px;
            margin: 40px auto;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .booking-form h2 {
            margin-bottom: 25px;
            font-size: 1.8em;
        }

        .booking-form label {
            display: block;
            margin: 12px 0 6px;
            font-weight: bold;
        }

        .booking-form input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .booking-form button {
            background: #d4a017;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1em;
            transition: background 0.3s;
        }

        .booking-form button:hover {
            background: #b38b12;
        }

        .confirmation {
            text-align: center;
            color: #28a745;
            margin: 25px;
            font-size: 1.2em;
        }

        .back-home {
            display: block;
            text-align: center;
            margin: 25px;
            color: #d4a017;
            text-decoration: none;
            font-size: 1.1em;
        }

        @media (max-width: 768px) {
            .booking-form {
                margin: 20px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Book Your Stay</h1>
    </header>

    <section class="booking-form">
        <h2>Booking for <?php echo htmlspecialchars($hotel['name']); ?></h2>
        <?php if (isset($confirmation_message)): ?>
            <p class="confirmation"><?php echo htmlspecialchars($confirmation_message); ?></p>
            <a href="index.php" class="back-home">Back to Home</a>
        <?php else: ?>
            <form method="POST">
                <label for="user_name">Full Name</label>
                <input type="text" id="user_name" name="user_name" required>

                <label for="user_email">Email</label>
                <input type="email" id="user_email" name="user_email" required>

                <label for="check_in">Check-In Date</label>
                <input type="date" id="check_in" name="check_in" value="<?php echo htmlspecialchars($checkIn); ?>" required>

                <label for="check_out">Check-Out Date</label>
                <input type="date" id="check_out" name="check_out" value="<?php echo htmlspecialchars($checkOut); ?>" required>

                <label for="total_price">Total Price ($)</label>
                <input type="number" id="total_price" name="total_price" value="<?php echo htmlspecialchars($hotel['price']); ?>" readonly>

                <button type="submit">Confirm Booking</button>
            </form>
            <a href="index.php" class="back-home">Back to Home</a>
        <?php endif; ?>
    </section>

    <script>
        function goToHome() {
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>
