<?php
require 'db.php';
$stmt = $pdo->query("SELECT * FROM hotels LIMIT 3");
$featured_hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilton Hotels - Homepage</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
 
        body {
            background: linear-gradient(135deg, #e6e9f0 0%, #d4e4f7 100%);
        }
 
        header {
            background: #003087;
            color: white;
            padding: 20px;
            text-align: center;
        }
 
        header h1 {
            font-size: 2.5em;
            letter-spacing: 1px;
        }
 
        .search-container {
            background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945') no-repeat center;
            background-size: cover;
            padding: 60px;
            text-align: center;
            color: white;
            box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.4);
        }
 
        .search-container h2 {
            font-size: 2.2em;
            margin-bottom: 25px;
        }
 
        .search-form {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
 
        .search-form input, .search-form select, .search-form button {
            padding: 12px;
            border: none;
            border-radius: 30px;
            font-size: 1em;
        }
 
        .search-form input, .search-form select {
            width: 220px;
            background: rgba(255, 255, 255, 0.95);
        }
 
        .search-form button {
            background: #d4a017;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }
 
        .search-form button:hover {
            background: #b38b12;
        }
 
        .filters {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin: 25px;
            flex-wrap: wrap;
        }
 
        .filters select {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
 
        .featured-hotels {
            padding: 50px;
            max-width: 1200px;
            margin: auto;
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
 
        @media (max-width: 768px) {
            .search-form input, .search-form select {
                width: 100%;
                margin-bottom: 15px;
            }
 
            .search-container {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Hilton Hotels</h1>
    </header>
 
    <section class="search-container">
        <h2>Discover Your Perfect Stay</h2>
        <form id="searchForm" class="search-form">
            <input type="text" id="destination" placeholder="Destination" required>
            <input type="date" id="checkIn" required>
            <input type="date" id="checkOut" required>
            <button type="submit">Search</button>
        </form>
    </section>
 
    <section class="filters">
        <select id="priceRange">
            <option value="">Price Range</option>
            <option value="100-200">$100 - $200</option>
            <option value="200-300">$200 - $300</option>
        </select>
        <select id="rating">
            <option value="">Rating</option>
            <option value="4">4+ Stars</option>
            <option value="3">3+ Stars</option>
        </select>
        <select id="amenities">
            <option value="">Amenities</option>
            <option value="WiFi">WiFi</option>
            <option value="Pool">Pool</option>
        </select>
    </section>
 
    <section class="featured-hotels">
        <h2>Featured Hotels</h2>
        <div class="hotel-grid">
            <?php foreach ($featured_hotels as $hotel): ?>
                <div class="hotel-card" onclick="goToHotel(<?php echo $hotel['id']; ?>)">
                    <img src="<?php echo htmlspecialchars($hotel['image']); ?>" alt="<?php echo htmlspecialchars($hotel['name']); ?>">
                    <h3><?php echo htmlspecialchars($hotel['name']); ?></h3>
                    <p><?php echo htmlspecialchars($hotel['location']); ?></p>
                    <p class="price">$<?php echo number_format($hotel['price'], 2); ?> / night</p>
                    <p>Rating: <?php echo htmlspecialchars($hotel['rating']); ?> ★</p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
 
    <script>
        function goToHotel(hotelId) {
            window.location.href = `hotels.php?hotel_id=${hotelId}`;
        }
 
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const destination = document.getElementById('destination').value;
            const checkIn = document.getElementById('checkIn').value;
            const checkOut = document.getElementById('checkOut').value;
            const priceRange = document.getElementById('priceRange').value;
            const rating = document.getElementById('rating').value;
            const amenities = document.getElementById('amenities').value;
 
            const query = new URLSearchParams({
                destination,
                checkIn,
                checkOut,
                priceRange,
                rating,
                amenities
            }).toString();
 
            window.location.href = `hotels.php?${query}`;
        });
    </script>
</body>
</html>
