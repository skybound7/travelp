<?php
require_once 'includes/header.php';
require_once 'includes/legal/DataRequestManager.php';

$dataManager = new DataRequestManager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $requestType = $_POST['request_type'];
        $userId = $_SESSION['user_id']; // Assuming user is logged in
        $additionalData = isset($_POST['additional_data']) ? json_decode($_POST['additional_data'], true) : [];
        
        $requestId = $dataManager->createDataRequest($userId, $requestType, $additionalData);
        $success = "Your request has been submitted successfully. Request ID: {$requestId}";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="container data-request">
    <h1>Data Privacy Request</h1>
    
    <?php if (isset($success)): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>
    
    <div class="request-types">
        <div class="request-type" data-type="export">
            <h3>Export Data</h3>
            <p>Request a copy of all your personal data</p>
            <button class="btn-request" data-type="export">Request Export</button>
        </div>
        
        <div class="request-type" data-type="delete">
            <h3>Delete Data</h3>
            <p>Request deletion of your personal data</p>
            <button class="btn-request" data-type="delete">Request Deletion</button>
        </div>
        
        <div class="request-type" data-type="update">
            <h3>Update Data</h3>
            <p>Request updates to your personal data</p>
            <button class="btn-request" data-type="update">Request Update</button>
        </div>
        
        <div class="request-type" data-type="restrict">
            <h3>Restrict Processing</h3>
            <p>Request restriction of your data processing</p>
            <button class="btn-request" data-type="restrict">Request Restriction</button>
        </div>
    </div>
    
    <div id="request-forms" class="request-forms">
        <!-- Export Data Form -->
        <form id="form-export" class="request-form" style="display: none;" method="POST">
            <input type="hidden" name="request_type" value="export">
            <h3>Export Data Request</h3>
            <p>Please select the data you'd like to export:</p>
            <div class="form-group">
                <label><input type="checkbox" name="data_types[]" value="personal" checked> Personal Information</label>
                <label><input type="checkbox" name="data_types[]" value="bookings" checked> Booking History</label>
                <label><input type="checkbox" name="data_types[]" value="preferences" checked> Preferences</label>
                <label><input type="checkbox" name="data_types[]" value="marketing" checked> Marketing Preferences</label>
            </div>
            <button type="submit" class="btn-primary">Submit Export Request</button>
        </form>
        
        <!-- Delete Data Form -->
        <form id="form-delete" class="request-form" style="display: none;" method="POST">
            <input type="hidden" name="request_type" value="delete">
            <h3>Delete Data Request</h3>
            <div class="form-group">
                <p class="warning">Warning: This action cannot be undone. Please read our data deletion policy before proceeding.</p>
                <label>
                    <input type="checkbox" name="confirm_deletion" required>
                    I understand that this action is permanent and cannot be undone
                </label>
            </div>
            <button type="submit" class="btn-danger">Submit Deletion Request</button>
        </form>
        
        <!-- Update Data Form -->
        <form id="form-update" class="request-form" style="display: none;" method="POST">
            <input type="hidden" name="request_type" value="update">
            <h3>Update Data Request</h3>
            <div class="form-group">
                <label>What information would you like to update?</label>
                <textarea name="update_details" required></textarea>
            </div>
            <button type="submit" class="btn-primary">Submit Update Request</button>
        </form>
        
        <!-- Restrict Processing Form -->
        <form id="form-restrict" class="request-form" style="display: none;" method="POST">
            <input type="hidden" name="request_type" value="restrict">
            <h3>Restrict Processing Request</h3>
            <div class="form-group">
                <label>Reason for restriction:</label>
                <select name="restriction_reason" required>
                    <option value="">Select a reason</option>
                    <option value="accuracy">Data accuracy disputed</option>
                    <option value="unlawful">Processing is unlawful</option>
                    <option value="legitimate">Legitimate interests override</option>
                    <option value="other">Other reason</option>
                </select>
            </div>
            <div class="form-group">
                <label>Additional details:</label>
                <textarea name="restriction_details"></textarea>
            </div>
            <button type="submit" class="btn-primary">Submit Restriction Request</button>
        </form>
    </div>
</div>

<style>
.data-request {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}

.request-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.request-type {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
}

.request-type h3 {
    color: var(--luxury-gold);
    margin-bottom: 10px;
}

.btn-request {
    background: var(--luxury-gold);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 15px;
}

.request-form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin: 20px 0;
}

.form-group {
    margin: 15px 0;
}

.form-group label {
    display: block;
    margin: 8px 0;
}

.warning {
    color: #dc3545;
    padding: 10px;
    background: #fff3f3;
    border-radius: 4px;
    margin: 10px 0;
}

textarea {
    width: 100%;
    min-height: 100px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin: 20px 0;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.btn-primary {
    background: var(--luxury-gold);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const requestButtons = document.querySelectorAll('.btn-request');
    const forms = document.querySelectorAll('.request-form');
    
    requestButtons.forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            
            // Hide all forms
            forms.forEach(form => form.style.display = 'none');
            
            // Show selected form
            document.getElementById(`form-${type}`).style.display = 'block';
            
            // Scroll to form
            document.getElementById(`form-${type}`).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
</script>

<?php
require_once 'includes/footer.php';
?>
