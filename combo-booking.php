<?php
require_once 'includes/config.php';
require_once 'includes/booking/ComboItinerary.php';

// Initialize booking manager
$comboBooking = new Booking\ComboItinerary($conn, $_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Create new itinerary
        $itineraryData = [
            'title' => $_POST['title'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'total_price' => calculateTotalPrice($_POST['components']),
            'components' => $_POST['components'],
            'schedule' => $_POST['schedule']
        ];
        
        $itineraryId = $comboBooking->createComboItinerary($itineraryData);
        
        // Process payment
        $paymentData = [
            'method' => $_POST['payment_method'],
            'transaction_id' => uniqid('PAY'),
            // Add other payment details as needed
        ];
        
        $paymentResult = $comboBooking->processPayment($itineraryId, $paymentData);
        
        // Redirect to confirmation page
        header("Location: booking-confirmation.php?id=" . $itineraryId);
        exit;
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

/**
 * Calculate total price of all components
 */
function calculateTotalPrice($components) {
    return array_reduce($components, function($total, $component) {
        return $total + $component['price'];
    }, 0);
}

// Get available components for booking
$availableFlights = getAvailableFlights();
$availableHotels = getAvailableHotels();
$availableActivities = getAvailableActivities();

/**
 * Get available flights
 */
function getAvailableFlights() {
    global $conn;
    // Implementation to fetch available flights
    return [];
}

/**
 * Get available hotels
 */
function getAvailableHotels() {
    global $conn;
    // Implementation to fetch available hotels
    return [];
}

/**
 * Get available activities
 */
function getAvailableActivities() {
    global $conn;
    // Implementation to fetch available activities
    return [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Custom Travel Package - Luxury Travel</title>
    <link rel="stylesheet" href="css/luxury.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Create Your Custom Travel Package</h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form id="combo-booking-form" method="POST" action="">
            <section class="booking-section">
                <h2>Basic Information</h2>
                <div class="form-group">
                    <label for="title">Package Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div>
            </section>
            
            <section class="booking-section" id="components-section">
                <h2>Add Components</h2>
                
                <div class="component-tabs">
                    <button type="button" class="tab-button active" data-tab="flights">Flights</button>
                    <button type="button" class="tab-button" data-tab="hotels">Hotels</button>
                    <button type="button" class="tab-button" data-tab="activities">Activities</button>
                </div>
                
                <div class="tab-content active" id="flights-tab">
                    <!-- Flight selection content -->
                </div>
                
                <div class="tab-content" id="hotels-tab">
                    <!-- Hotel selection content -->
                </div>
                
                <div class="tab-content" id="activities-tab">
                    <!-- Activity selection content -->
                </div>
            </section>
            
            <section class="booking-section" id="schedule-section">
                <h2>Daily Schedule</h2>
                <div id="schedule-builder">
                    <!-- Schedule builder will be populated via JavaScript -->
                </div>
                <button type="button" id="add-day" class="btn-secondary">Add Day</button>
            </section>
            
            <section class="booking-section" id="payment-section">
                <h2>Payment Information</h2>
                
                <div class="total-price">
                    <h3>Total Price: <span id="total-amount">$0.00</span></h3>
                </div>
                
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                
                <div id="credit-card-fields">
                    <div class="form-group">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" name="card_number" pattern="\d{16}" placeholder="1234 5678 9012 3456">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="text" id="expiry_date" name="expiry_date" pattern="\d{2}/\d{2}" placeholder="MM/YY">
                        </div>
                        
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" pattern="\d{3,4}" placeholder="123">
                        </div>
                    </div>
                </div>
            </section>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">Confirm Booking</button>
                <button type="button" class="btn-secondary" onclick="history.back()">Cancel</button>
            </div>
        </form>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/combo-booking.js"></script>
    <script>
        // Initialize the booking form
        document.addEventListener('DOMContentLoaded', function() {
            initializeBookingForm({
                flights: <?php echo json_encode($availableFlights); ?>,
                hotels: <?php echo json_encode($availableHotels); ?>,
                activities: <?php echo json_encode($availableActivities); ?>
            });
        });
    </script>
</body>
</html>
