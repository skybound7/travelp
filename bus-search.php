<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/header.php';

$origin = $_GET['origin'] ?? '';
$destination = $_GET['destination'] ?? '';
$travel_date = $_GET['travel_date'] ?? '';

$buses = [];
if ($origin && $destination && $travel_date) {
    $stmt = $pdo->prepare("
        SELECT bs.*, bo.name as operator_name, bt.type as bus_type, 
               br.origin, br.destination, br.distance_km, br.duration_minutes
        FROM bus_schedules bs
        JOIN bus_operators bo ON bs.operator_id = bo.id
        JOIN bus_types bt ON bs.bus_type_id = bt.id
        JOIN bus_routes br ON bs.route_id = br.id
        WHERE br.origin LIKE :origin 
        AND br.destination LIKE :destination
        AND DATE(bs.departure_time) = :travel_date
        AND bs.available_seats > 0
        ORDER BY bs.departure_time
    ");
    
    $stmt->execute([
        ':origin' => "%$origin%",
        ':destination' => "%$destination%",
        ':travel_date' => $travel_date
    ]);
    
    $buses = $stmt->fetchAll();
}
?>

<div class="container mt-4">
    <h2>Search Bus Tickets</h2>
    
    <form method="GET" action="" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="origin">From</label>
                    <input type="text" class="form-control" id="origin" name="origin" 
                           value="<?php echo htmlspecialchars($origin); ?>" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="destination">To</label>
                    <input type="text" class="form-control" id="destination" name="destination" 
                           value="<?php echo htmlspecialchars($destination); ?>" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="travel_date">Travel Date</label>
                    <input type="date" class="form-control" id="travel_date" name="travel_date" 
                           value="<?php echo htmlspecialchars($travel_date); ?>" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Search Buses</button>
                </div>
            </div>
        </div>
    </form>

    <?php if (!empty($buses)): ?>
        <div class="row">
            <?php foreach ($buses as $bus): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($bus['operator_name']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                <?php echo htmlspecialchars($bus['bus_type']); ?>
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Departure:</strong> 
                                        <?php echo date('h:i A', strtotime($bus['departure_time'])); ?></p>
                                    <p><strong>Duration:</strong> 
                                        <?php echo floor($bus['duration_minutes']/60).'h '.($bus['duration_minutes']%60).'m'; ?></p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Available Seats:</strong> 
                                        <?php echo htmlspecialchars($bus['available_seats']); ?></p>
                                    <p><strong>Fare:</strong> ₹<?php echo htmlspecialchars($bus['base_fare']); ?></p>
                                </div>
                            </div>
                            <a href="bus-booking.php?schedule_id=<?php echo $bus['id']; ?>" 
                               class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['origin'])): ?>
        <div class="alert alert-info">No buses found for the selected criteria.</div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
