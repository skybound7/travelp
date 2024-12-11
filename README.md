# PhonePe Payment Integration

This project implements a PhonePe payment gateway integration.

## Setup Instructions

1. Install PHP 8.2:
   - Download from: https://windows.php.net/download/
   - Extract to C:\php
   - Add C:\php to system PATH
   - Copy php.ini-development to php.ini

2. Configure Database:
   - Copy database/config.template.php to database/config.php
   - Update database credentials in config.php

3. Configure PhonePe:
   - Copy includes/config/payment.template.php to includes/config/payment.php
   - Update PhonePe credentials and site URL

4. Run Database Migration:
   ```bash
   cd database
   php run_migration.php
   ```

## Testing

Test the payment flow using cURL:
```bash
curl -X POST http://your-domain/api/payment/initiate.php \
-H "Content-Type: application/json" \
-d '{
    "bookingId": "123",
    "bookingType": "test",
    "paymentMethod": "phonepe",
    "amount": 100,
    "paymentDetails": {
        "userId": "user123",
        "mobileNumber": "9999999999"
    }
}'
```

## Directory Structure

- api/payment/ - Payment API endpoints
- includes/
  - config/ - Configuration files
  - services/ - Service classes
  - payments/ - Payment processors
- database/ - Database migrations and config
