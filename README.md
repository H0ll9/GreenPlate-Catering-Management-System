##  Database Setup (MySQL)
```
CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id VARCHAR(20) NOT NULL UNIQUE,
    customer_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    location TEXT NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    num_people INT NOT NULL,
    instructions TEXT,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

