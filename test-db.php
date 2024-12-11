<?php
require_once 'includes/config.php';
require_once 'includes/booking/ComboItinerary.php';

// Test database connection
echo "<h2>Testing Database Connection</h2>";
if ($conn->ping()) {
    echo "Database connection successful!<br><br>";
} else {
    die("Database connection failed!");
}

// Initialize booking manager
$comboBooking = new Booking\ComboItinerary($conn, 1); // Using user_id = 1 for testing

// Test Query 1: View all itineraries with components
echo "<h3>All Itineraries with Components:</h3>";
$query = "
    SELECT ci.title, ci.start_date, ci.end_date, 
           ic.component_type, ic.price
    FROM combo_itineraries ci
    JOIN itinerary_components ic ON ci.id = ic.itinerary_id
";

$result = $conn->query($query);
if ($result) {
    echo "<table border='1'>
        <tr>
            <th>Title</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Component</th>
            <th>Price</th>
        </tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['start_date']}</td>
            <td>{$row['end_date']}</td>
            <td>{$row['component_type']}</td>
            <td>\${$row['price']}</td>
        </tr>";
    }
    echo "</table><br>";
}

// Test Query 2: View detailed schedule
echo "<h3>Detailed Schedule for Itinerary #1:</h3>";
$query = "
    SELECT day_number, time_slot, activity_type, 
           description, location, duration
    FROM itinerary_schedule
    WHERE itinerary_id = 1
    ORDER BY day_number, time_slot
";

$result = $conn->query($query);
if ($result) {
    echo "<table border='1'>
        <tr>
            <th>Day</th>
            <th>Time</th>
            <th>Activity</th>
            <th>Description</th>
            <th>Location</th>
            <th>Duration</th>
        </tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['day_number']}</td>
            <td>{$row['time_slot']}</td>
            <td>{$row['activity_type']}</td>
            <td>{$row['description']}</td>
            <td>{$row['location']}</td>
            <td>{$row['duration']}</td>
        </tr>";
    }
    echo "</table><br>";
}

// Test Query 3: View payment status
echo "<h3>Payment Status:</h3>";
$query = "
    SELECT ci.title, p.amount, p.payment_method, 
           p.status, p.created_at
    FROM payments p
    JOIN combo_itineraries ci ON p.itinerary_id = ci.id
";

$result = $conn->query($query);
if ($result) {
    echo "<table border='1'>
        <tr>
            <th>Itinerary</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['title']}</td>
            <td>\${$row['amount']}</td>
            <td>{$row['payment_method']}</td>
            <td>{$row['status']}</td>
            <td>{$row['created_at']}</td>
        </tr>";
    }
    echo "</table><br>";
}

// Test creating a new itinerary
echo "<h3>Testing New Itinerary Creation:</h3>";
try {
    $newItineraryData = [
        'title' => 'Test Luxury Package',
        'start_date' => '2024-08-01',
        'end_date' => '2024-08-10',
        'total_price' => 3500.00,
        'components' => [
            [
                'type' => 'flight',
                'service_id' => 'FL999',
                'start_datetime' => '2024-08-01 10:00:00',
                'end_datetime' => '2024-08-01 15:00:00',
                'price' => 1000.00,
                'details' => [
                    'airline' => 'Test Airways',
                    'class' => 'First',
                    'origin' => 'Test City',
                    'destination' => 'Paradise City'
                ]
            ]
        ],
        'schedule' => [
            1 => [
                [
                    'time' => '10:00:00',
                    'type' => 'transport',
                    'description' => 'Departure Flight',
                    'location' => 'Test Airport',
                    'duration' => '5 hours',
                    'notes' => 'First class service'
                ]
            ]
        ]
    ];
    
    $newItineraryId = $comboBooking->createComboItinerary($newItineraryData);
    echo "Successfully created new itinerary with ID: $newItineraryId<br>";
    
} catch (Exception $e) {
    echo "Error creating itinerary: " . $e->getMessage() . "<br>";
}

$conn->close();
