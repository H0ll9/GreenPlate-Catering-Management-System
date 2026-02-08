# 🌿 GreenPlate Catering Management System
A complete, responsive web application for managing vegetarian catering requests. This system separates the Client interface from the Admin dashboard, allowing customers to book events and administrators to manage approvals effortlessly.

## ✨ Features
### For Clients (Public)
- Beautiful Landing Page: A modern, responsive home page with marketing content.
- Online Booking: Submit catering requests (Date, Time, Location, Guest Count) via a user-friendly form.
- Order Tracking: Check the status of any request using a phone number (In-App Notification system).

### For Admin (Private)
- Dashboard: View a summary of Total, Pending, and Approved requests.
- Request Management:
- View Details: Click the "👁️" eye button to see full form details in a modal popup.
- Approve/Reject: Change the status of requests with a single click.
- Delete: Remove invalid or old requests permanently from the database.

## Tech Stack
- Frontend: HTML5, CSS3 (Custom Design), Vanilla JavaScript.
- Backend: PHP 7/8.
- Database: MySQL.
- Server: Apache (via XAMPP).

## How to Run
### Project Setup
- Navigate to your XAMPP installation folder (usually `C:\xampp\htdocs\`).
- clone `https://github.com/H0ll9/GreenPlate-Catering-Management-System.git` inside `C:\xampp\htdocs\` folder.

###  Database Setup (MySQL)
- Open XAMPP Control Panel and start Apache and MySQL.
- Create database called `catering_db`
- Run this query once to create table inside `catering_db` database.

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

## Configuration
- Open `config/db.php`.
- Ensure the username is root and password is empty (default for XAMPP). If you set a MySQL password, update it there.

## Launch
- Open `http://localhost/catering_app/index.php` (`catering_app` can be deferent if you choose deferent name for the project folder.)

# 📖 Demonstration (How to Use)
## Home Page
<img width="1903" height="979" alt="Image" src="https://github.com/user-attachments/assets/51fa74de-19ab-403e-a8f0-fc672fbf94ef" />
## Scenario 1: Customer submits a request
- On the Home Page, click the "Book Now" button.
- Fill in the Booking Form (Name, Phone, Date, Location, etc.).
- Click Submit Request.
- You will see a success message with a Request ID (e.g., REQ-1234).
<img width="1905" height="986" alt="Image" src="https://github.com/user-attachments/assets/a08f6a2c-2c26-4677-a9fb-5c1ba9aa3a3a" />

## Scenario 2: Admin manages the request
- Scroll to the bottom of the Home Page and click "Admin Staff Login" (or go to admin.php).
- You will see the new request in the table with status "Pending".
- View Details: Click the Blue Eye (👁️) icon to see the full form details.
- Approve: Click the Green Check (✓) button.
- The status updates to "Approved" in the database.
<img width="1908" height="951" alt="Image" src="https://github.com/user-attachments/assets/f8f3a3ec-8fd5-4ea0-9434-380f7196dab9" />

<img width="1907" height="973" alt="Image" src="https://github.com/user-attachments/assets/62d391d1-8330-4ba7-b356-e144cbb6dcc7" />

## Scenario 3: Customer checks status
- Go back to the Home Page.
- Click "Track Request" in the navigation bar.
- Enter the phone number used in Scenario 1.
- The page displays the request with a Green "Approved" Badge.

<img width="1898" height="978" alt="Image" src="https://github.com/user-attachments/assets/d6bafef7-8ba7-46c3-8413-cdcd43ff42e0" />
