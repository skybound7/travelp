<?php
require_once 'includes/header.php';
?>

<div class="container legal-document refund-policy">
    <h1>Refund Policy</h1>
    <p>Last updated: <?php echo date('F d, Y'); ?></p>

    <section>
        <h2>1. Standard Cancellation Policy</h2>
        <div class="policy-table">
            <table>
                <thead>
                    <tr>
                        <th>Time Before Travel</th>
                        <th>Refund Amount</th>
                        <th>Conditions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>60+ days</td>
                        <td>100% refund</td>
                        <td>Less processing fees</td>
                    </tr>
                    <tr>
                        <td>30-59 days</td>
                        <td>75% refund</td>
                        <td>Less processing fees</td>
                    </tr>
                    <tr>
                        <td>15-29 days</td>
                        <td>50% refund</td>
                        <td>Less processing fees</td>
                    </tr>
                    <tr>
                        <td>7-14 days</td>
                        <td>25% refund</td>
                        <td>Less processing fees</td>
                    </tr>
                    <tr>
                        <td>Less than 7 days</td>
                        <td>No refund</td>
                        <td>Credit may be available</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <h2>2. Flexible Booking Options</h2>
        <div class="flexible-options">
            <h3>Premium Flexibility Package</h3>
            <ul>
                <li>Free cancellation up to 24 hours before travel</li>
                <li>One free date change</li>
                <li>100% credit for future bookings</li>
                <li>Additional cost: 15% of booking value</li>
            </ul>
        </div>
    </section>

    <section>
        <h2>3. Force Majeure</h2>
        <p>In cases of force majeure (natural disasters, pandemics, etc.):</p>
        <ul>
            <li>100% credit for future bookings</li>
            <li>Flexible rebooking options</li>
            <li>Extended validity period</li>
        </ul>
    </section>

    <section>
        <h2>4. Refund Process</h2>
        <div class="process-steps">
            <div class="step">
                <span class="step-number">1</span>
                <h4>Submit Request</h4>
                <p>Contact our customer service or submit online form</p>
            </div>
            <div class="step">
                <span class="step-number">2</span>
                <h4>Review</h4>
                <p>We'll review your request within 48 hours</p>
            </div>
            <div class="step">
                <span class="step-number">3</span>
                <h4>Approval</h4>
                <p>Receive confirmation of refund amount</p>
            </div>
            <div class="step">
                <span class="step-number">4</span>
                <h4>Processing</h4>
                <p>Refund processed within 5-10 business days</p>
            </div>
        </div>
    </section>

    <section>
        <h2>5. Non-Refundable Items</h2>
        <ul>
            <li>Processing fees</li>
            <li>Travel insurance premiums</li>
            <li>Visa processing fees</li>
            <li>Special event tickets</li>
        </ul>
    </section>
</div>

<style>
.legal-document {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}

.policy-table table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.policy-table th, .policy-table td {
    padding: 15px;
    text-align: left;
    border: 1px solid #eee;
}

.policy-table th {
    background: var(--luxury-gold);
    color: white;
}

.policy-table tr:nth-child(even) {
    background: #f9f9f9;
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.step {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
    position: relative;
}

.step-number {
    background: var(--luxury-gold);
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.flexible-options {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.flexible-options h3 {
    color: var(--luxury-gold);
    margin-bottom: 15px;
}

.flexible-options ul {
    list-style-type: none;
    padding: 0;
}

.flexible-options li {
    margin: 10px 0;
    padding-left: 25px;
    position: relative;
}

.flexible-options li:before {
    content: "✓";
    color: var(--luxury-gold);
    position: absolute;
    left: 0;
}
</style>

<?php
require_once 'includes/footer.php';
?>
