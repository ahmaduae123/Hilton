<?php
require 'db.php';

$destination = isset($_GET['destination']) ? $_GET['destination'] : '';
$checkIn = isset($_GET['checkIn']) ? $_GET['checkIn'] : '';
$checkOut = isset($_GET['checkOut']) ? $_GET['checkOut'] : '';
$priceRange = isset($_GET['priceRange']) ? $_GET['priceRange'] : '';
$rating = isset($_GET['rating']) ? $_GET['rating'] : '';
$amenities = isset($_GET['amenities']) ? $_GET['amenities'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';

$query = "SELECT * FROM hotels WHERE 1=1";
$params = [];

if ($destination) {
    $query .= " AND location LIKE ?";
    $params[] = "%$destination%";
}

if ($priceRange) {
    list($min, $max) = explode('-', $priceRange);
    $query .= " AND price BETWEEN ? AND ?";
    $params[] = $min;
    $params[] = $max;
}

if ($rating) {
    $query .= " AND rating >= ?";
    $params[] = $rating;
}

if ($amenities) {
    $query .= " AND amenities LIKE ?";
    $params[] = "%$amenities%";
}

if ($sort == 'price_asc') {
    $query .= " ORDER BY price ASC";
} elseif ($sort == 'price_desc') {
    $query .= " ORDER BY price DESC";
} elseif ($sort == 'rating_desc') {
    $query .= " ORDER BY rating DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels - Hilton Clone</title>
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

        .hotel-list {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .sort-filter {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .sort-filter select {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .hotel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
        }

        .hotel-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .hotel-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .hotel-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .hotel-card h3 {
            font-size: 1.6em;
            padding: 12px;
        }

        .hotel-card p {
            padding: 0 12px 12px;
            color: #555;
        }

        .hotel-card .price {
            font-weight: bold;
            color: #d4a017;
        }

        .book-now {
            display: block;
            margin: 12px;
            padding: 12px;
            background: #d4a017;
            color: white;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .book-now:hover {
            background: #b38b12;
        }

        @media (max-width: 768px) {
            .sort-filter {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Available Hotels</h1>
    </header>

    <section class="hotel-list">
        <div class="sort-filter">
            <select id="sort" onchange="applySort()">
                <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                <option value="rating_desc" <?php echo $sort == 'rating_desc' ? 'selected' : ''; ?>>Best Rated</option>
            </select>
        </div>

        <div class="hotel-grid">
            <?php if (count($hotels) > 0): ?>
                <?php foreach ($hotels as $hotel): ?>
                    <div class="hotel-card">
                        <img src="<?php echo htmlspecialchars($hotel['image']); ?>" alt="<?php echo htmlspecialchars($hotel['name']); ?>">
                        <h3><?php echo htmlspecialchars($hotel['name']); ?></h3>
                        <p><?php echo htmlspecialchars($hotel['location']); ?></p>
                        <p class="price">$<?php echo number_format($hotel['price'], 2); ?> / night</p>
                        <p>Rating: <?php echo htmlspecialchars($hotel['rating']); ?> ★</p>
                        <p><?php echo htmlspecialchars($hotel['description']); ?></p>
                        <a href="#" class="book-now" onclick="goToBooking(<?php echo $hotel['id']; ?>, '<?php echo $checkIn; ?>', '<?php echo $checkOut; ?>')">Book Now</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hotels found matching your criteria.</p>
            <?php endif; ?>
        </div>
    </section>

    <script>
        function applySort() {
            const sort = document.getElementById('sort').value;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sort);
            window.location.href = url.toString();
        }

        function goToBooking(hotelId, checkIn, checkOut) {
            const query = new URLSearchParams({
                hotel_id: hotelId,
                checkIn: checkIn,
                checkOut: checkOut
            }).toString();
            window.location.href = `booking.php?${query}`;
        }
    </script>
</body>
</html>
