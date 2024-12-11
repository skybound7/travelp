<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/header.php';

$from_station = $_GET['from_station'] ?? '';
$to_station = $_GET['to_station'] ?? '';
$travel_date = $_GET['travel_date'] ?? '';
$class_id = $_GET['class_id'] ?? '';

// Fetch available train classes
$stmt = $pdo->query("SELECT * FROM train_classes ORDER BY id");
$train_classes = $stmt->fetchAll();

$trains = [];
if ($from_station && $to_station && $travel_date) {
    $query = "
        SELECT t.*, tt.name as train_type, tt.code as train_type_code,
               fs.code as from_station_code, fs.name as from_station_name,
               ts.code as to_station_code, ts.name as to_station_name,
               tsch1.departure_time as departure_time,
               tsch2.arrival_time as arrival_time,
               tf.base_fare, tf.tatkal_fare
        FROM trains t
        JOIN train_types tt ON t.type_id = tt.id
        JOIN railway_stations fs ON t.source_station_id = fs.id
        JOIN railway_stations ts ON t.destination_station_id = ts.id
        JOIN train_schedules tsch1 ON t.id = tsch1.train_id
        JOIN train_schedules tsch2 ON t.id = tsch2.train_id
        JOIN train_fares tf ON t.id = tf.train_id
        WHERE (fs.code LIKE :from_station OR fs.name LIKE :from_station_name)
        AND (ts.code LIKE :to_station OR ts.name LIKE :to_station_name)
    ";
    
    if ($class_id) {
        $query .= " AND tf.class_id = :class_id";
    }
    
    $stmt = $pdo->prepare($query);
    
    $params = [
        ':from_station' => "%$from_station%",
        ':from_station_name' => "%$from_station%",
        ':to_station' => "%$to_station%",
        ':to_station_name' => "%$to_station%"
    ];
    
    if ($class_id) {
        $params[':class_id'] = $class_id;
    }
    
    $stmt->execute($params);
    $trains = $stmt->fetchAll();
}
?>

<div class="container mt-4">
    <h2>Search Train Tickets</h2>
    
    <form method="GET" action="" class="mb-4">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="from_station">From Station</label>
                    <input type="text" class="form-control" id="from_station" name="from_station" 
                           placeholder="Station name or code"
                           value="<?php echo htmlspecialchars($from_station); ?>" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="to_station">To Station</label>
                    <input type="text" class="form-control" id="to_station" name="to_station" 
                           placeholder="Station name or code"
                           value="<?php echo htmlspecialchars($to_station); ?>" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="travel_date">Travel Date</label>
                    <input type="date" class="form-control" id="travel_date" name="travel_date" 
                           value="<?php echo htmlspecialchars($travel_date); ?>" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="class_id">Class</label>
                    <select class="form-control" id="class_id" name="class_id">
                        <option value="">All Classes</option>
                        <?php foreach ($train_classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"
                                    <?php echo ($class_id == $class['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($class['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Search Trains</button>
                </div>
            </div>
        </div>
    </form>

    <?php if (!empty($trains)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Train</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Duration</th>
                        <th>Classes</th>
                        <th>Fare</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trains as $train): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($train['number']); ?></strong><br>
                                <?php echo htmlspecialchars($train['name']); ?><br>
                                <small class="text-muted"><?php echo htmlspecialchars($train['train_type']); ?></small>
                            </td>
                            <td>
                                <?php echo date('h:i A', strtotime($train['departure_time'])); ?><br>
                                <small><?php echo htmlspecialchars($train['from_station_name']); ?></small>
                            </td>
                            <td>
                                <?php echo date('h:i A', strtotime($train['arrival_time'])); ?><br>
                                <small><?php echo htmlspecialchars($train['to_station_name']); ?></small>
                            </td>
                            <td>
                                <?php 
                                    $duration = strtotime($train['arrival_time']) - strtotime($train['departure_time']);
                                    echo floor($duration/3600).'h '.($duration/60%60).'m';
                                ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($train['class_code'] ?? 'All Classes'); ?>
                            </td>
                            <td>
                                ₹<?php echo htmlspecialchars($train['base_fare']); ?><br>
                                <?php if ($train['tatkal_fare']): ?>
                                    <small>Tatkal: ₹<?php echo htmlspecialchars($train['tatkal_fare']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="train-booking.php?train_id=<?php echo $train['id']; ?>&class_id=<?php echo $class_id; ?>&travel_date=<?php echo urlencode($travel_date); ?>" 
                                   class="btn btn-primary btn-sm">Book Now</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['from_station'])): ?>
        <div class="alert alert-info">No trains found for the selected criteria.</div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
