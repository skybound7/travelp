<?php
require_once 'includes/header.php';
require_once 'includes/booking-system/database.php';
require_once 'includes/booking-system/Booking.php';

// Get available services
$query = "SELECT * FROM services WHERE active = 1 ORDER BY name";
$services = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
?>

<div class="booking-container">
    <div class="booking-header">
        <h1>Book Your Luxury Experience</h1>
        <p class="subtitle">Customize your perfect getaway</p>
    </div>

    <form id="booking-form" class="luxury-form" autocomplete="off">
        <div class="form-progress">
            <div class="progress-step active" data-step="1">Service</div>
            <div class="progress-step" data-step="2">Details</div>
            <div class="progress-step" data-step="3">Customize</div>
            <div class="progress-step" data-step="4">Confirm</div>
        </div>

        <!-- Step 1: Service Selection -->
        <div class="form-step active" data-step="1">
            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                <div class="service-card" data-service-id="<?= $service['id'] ?>">
                    <div class="service-image">
                        <img src="assets/images/services/<?= $service['image'] ?>" alt="<?= htmlspecialchars($service['name']) ?>">
                    </div>
                    <div class="service-info">
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                        <div class="service-price">
                            From $<?= number_format($service['price'], 2) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Step 2: Booking Details -->
        <div class="form-step" data-step="2">
            <div class="form-group">
                <label for="booking-date">Select Date</label>
                <input type="text" id="booking-date" name="booking_date" class="booking-date" required>
                <div class="date-availability"></div>
            </div>

            <div class="form-group">
                <label for="guests">Number of Guests</label>
                <div class="guest-selector">
                    <button type="button" class="guest-btn minus">-</button>
                    <input type="number" id="guests" name="guests" value="2" min="1" required>
                    <button type="button" class="guest-btn plus">+</button>
                </div>
                <span class="guest-limit"></span>
            </div>

            <div class="time-slots">
                <label>Available Time Slots</label>
                <div class="time-grid"></div>
            </div>
        </div>

        <!-- Step 3: Customization -->
        <div class="form-step" data-step="3">
            <div class="preferences-section">
                <h3>Enhance Your Experience</h3>
                <div class="preferences-grid"></div>
            </div>

            <div class="special-requests">
                <label for="special-requests">Special Requests</label>
                <textarea id="special-requests" name="special_requests" rows="4"></textarea>
            </div>
        </div>

        <!-- Step 4: Confirmation -->
        <div class="form-step" data-step="4">
            <div class="booking-summary">
                <h3>Booking Summary</h3>
                <div class="summary-content"></div>
                <div class="price-breakdown">
                    <div class="base-price"></div>
                    <div class="preferences-cost"></div>
                    <div class="total-price"></div>
                </div>
            </div>

            <div class="payment-section">
                <h3>Secure Payment</h3>
                <div id="card-element"></div>
                <div id="card-errors"></div>
            </div>
        </div>

        <div class="form-navigation">
            <button type="button" class="btn-prev" style="display: none;">Previous</button>
            <button type="button" class="btn-next">Next</button>
            <button type="submit" class="btn-submit" style="display: none;">Confirm Booking</button>
        </div>
    </form>
</div>

<!-- Add CSS for the booking form -->
<style>
.booking-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    background: var(--luxury-cream);
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.booking-header {
    text-align: center;
    margin-bottom: 3rem;
}

.booking-header h1 {
    color: var(--luxury-gold-dark);
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.form-progress {
    display: flex;
    justify-content: space-between;
    margin-bottom: 3rem;
    position: relative;
}

.progress-step {
    flex: 1;
    text-align: center;
    position: relative;
    padding: 1rem;
    color: var(--luxury-black);
    font-weight: 500;
}

.progress-step::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    background: var(--luxury-gold);
    border-radius: 50%;
    transform: translate(-50%, -50%);
}

.progress-step.active::after {
    background: var(--luxury-gold-dark);
    box-shadow: 0 0 0 4px var(--luxury-gold-light);
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.service-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.service-card:hover {
    transform: translateY(-5px);
}

.service-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.service-info {
    padding: 1.5rem;
}

.service-price {
    color: var(--luxury-gold-dark);
    font-weight: bold;
    font-size: 1.2rem;
    margin-top: 1rem;
}

.form-group {
    margin-bottom: 2rem;
}

.guest-selector {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.guest-btn {
    width: 40px;
    height: 40px;
    border: 2px solid var(--luxury-gold);
    background: transparent;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

.guest-btn:hover {
    background: var(--luxury-gold);
    color: white;
}

.time-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.time-slot {
    padding: 0.8rem;
    text-align: center;
    border: 2px solid var(--luxury-gold-light);
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.time-slot:hover {
    background: var(--luxury-gold-light);
}

.time-slot.selected {
    background: var(--luxury-gold);
    color: white;
}

.preferences-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.preference-item {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s ease;
}

.preference-item:hover {
    border-color: var(--luxury-gold);
}

.preference-item.selected {
    border-color: var(--luxury-gold);
    background: var(--luxury-gold-light);
}

.booking-summary {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.price-breakdown {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--luxury-gold-light);
}

.form-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--luxury-gold-light);
}

.btn-prev, .btn-next, .btn-submit {
    padding: 1rem 2rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-next, .btn-submit {
    background: var(--luxury-gold);
    color: white;
}

.btn-prev {
    background: transparent;
    border: 2px solid var(--luxury-gold);
    color: var(--luxury-gold);
}

.btn-next:hover, .btn-submit:hover {
    background: var(--luxury-gold-dark);
}

.btn-prev:hover {
    background: var(--luxury-gold-light);
}
</style>

<?php require_once 'includes/footer.php'; ?>
