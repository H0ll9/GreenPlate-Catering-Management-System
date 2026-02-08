<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f7f9fc; margin: 0; padding: 0; }
        
        /* Header */
        header { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); z-index: 10; position: relative; }
        h1 { margin: 0; color: #2d3436; }
        .btn-refresh { background: #0984e3; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; }
        
        /* Main Container */
        .container { padding: 30px; max-width: 1200px; margin: 0 auto; }
        
        /* Stats Cards */
        .stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; }
        .stat-num { font-size: 24px; font-weight: bold; color: #ff6b6b; }
        
        /* Table */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #f1f1f1; }
        th { background: #f8f9fa; font-weight: 600; color: #636e72; }
        .status { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status.Pending { background: #fff3cd; color: #856404; }
        .status.Approved { background: #d4edda; color: #155724; }
        .status.Rejected { background: #f8d7da; color: #721c24; }
        
        /* Action Buttons */
        .actions button { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px; color: white; font-size: 14px; }
        .btn-view { background: #74b9ff; } /* Blue Eye */
        .btn-approve { background: #00b809; }
        .btn-reject { background: #f51d1d; }
        .btn-delete { background: #282d2e; } 

        /* MODAL STYLES */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000;
        }
        .modal-overlay.open { display: flex; }
        .modal-content {
            background: white; padding: 25px; border-radius: 12px; width: 90%; max-width: 500px;
            position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;
        }
        .modal-close { position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 24px; cursor: pointer; color: #aaa; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #eee; padding-bottom: 5px; }
        .detail-label { font-weight: 600; color: #636e72; width: 40%; }

        /* Toast Notification */
        #toast { visibility: hidden; min-width: 250px; background-color: #333; color: #fff; text-align: center; border-radius: 4px; padding: 16px; position: fixed; z-index: 2000; bottom: 30px; right: 30px; }
        #toast.show { visibility: visible; animation: fadein 0.5s, fadeout 0.5s 2.5s; }
        @keyframes fadein { from {bottom: 0; opacity: 0;} to {bottom: 30px; opacity: 1;} }
        @keyframes fadeout { from {bottom: 30px; opacity: 1;} to {bottom: 0; opacity: 0;} }
    </style>
</head>
<body>

<header>
    <h1>🍽️ Admin Dashboard</h1>
    <button class="btn-refresh" onclick="loadData()">Refresh Data</button>
</header>

<div class="container">
    <!-- Stats -->
    <div class="stats">
        <div class="stat-card"><div class="stat-num" id="count-total">0</div><div>Total Requests</div></div>
        <div class="stat-card"><div class="stat-num" id="count-pending" style="color:#fdcb6e">0</div><div>Pending</div></div>
        <div class="stat-card"><div class="stat-num" id="count-approved" style="color:#00b894">0</div><div>Approved</div></div>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date/Time</th>
                <th>Customer</th>
                <th>Location</th>
                <th>People</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Data loads here -->
        </tbody>
    </table>
</div>

<!-- DETAILS MODAL (Make sure this is in your file) -->
<div class="modal-overlay" id="detailsModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <h3 style="margin-bottom: 20px; color: #2d3436; border-bottom: 2px solid #ff6b6b; padding-bottom: 10px;">Full Request Details</h3>
        <div id="modalBody">
            <!-- Details injected via JS -->
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast">Message</div>

<script>
    let requests = [];

    // 1. LOAD DATA
    function loadData() {
        console.log("Loading data...");
        fetch('./api/read.php')
            .then(res => {
                if(!res.ok) throw new Error("Network response was not ok");
                return res.json();
            })
            .then(data => {
                requests = data;
                console.log("Data received:", requests);
                renderTable();
                updateStats();
            })
            .catch(err => {
                console.error("Error loading data:", err);
                showToast("Error loading data");
            });
    }

    // 2. UPDATE STATUS
    function updateStatus(id, status) {
        if(!confirm(`Mark as ${status}?`)) return;

        fetch('./api/update.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id: id, status: status })
        })
        .then(res => res.json())
        .then(result => {
            showToast(result.status === 'success' ? "Status Updated" : "Error");
            loadData();
        });
    }

    // 3. DELETE RECORD
    function deleteRequest(id) {
        if(!confirm("Are you SURE you want to delete this record? This cannot be undone.")) return;

        fetch('./api/delete.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(result => {
            showToast(result.status === 'success' ? "Record Deleted" : "Error");
            loadData();
        });
    }

    // 4. VIEW DETAILS (The Eye Button) - FIXED VERSION
    function viewDetails(id) {
        console.log("View Details Clicked. ID passed:", id);
        console.log("Current requests array:", requests);

        // Find the record
        const req = requests.find(r => r.id == id); // Using == for loose comparison (string vs number)
        
        if (!req) {
            console.error("Request not found for ID:", id);
            alert("Error: Could not find request details in loaded data.");
            return;
        }
        
        console.log("Found Request:", req);

        const modalBody = document.getElementById('modalBody');
        const modalElement = document.getElementById('detailsModal');

        if (!modalBody || !modalElement) {
            console.error("Modal HTML elements are missing!");
            return;
        }

        modalBody.innerHTML = `
            <div class="detail-row"><span class="detail-label">Request ID:</span> <span>${req.request_id}</span></div>
            <div class="detail-row"><span class="detail-label">Customer Name:</span> <span>${req.customer_name}</span></div>
            <div class="detail-row"><span class="detail-label">Contact Number:</span> <span>${req.contact_number}</span></div>
            <div class="detail-row"><span class="detail-label">Event Date:</span> <span>${req.event_date}</span></div>
            <div class="detail-row"><span class="detail-label">Event Time:</span> <span>${req.event_time}</span></div>
            <div class="detail-row"><span class="detail-label">Location:</span> <span>${req.location}</span></div>
            <div class="detail-row"><span class="detail-label">Event Type:</span> <span>${req.event_type}</span></div>
            <div class="detail-row"><span class="detail-label">Number of People:</span> <span>${req.num_people}</span></div>
            <div class="detail-row"><span class="detail-label">Status:</span> <span class="status ${req.status}">${req.status}</span></div>
            <div style="margin-top: 15px;">
                <span class="detail-label" style="display:block; margin-bottom:5px;">Special Instructions:</span>
                <div style="background: #f1f2f6; padding: 10px; border-radius: 6px; font-size: 14px;">
                    ${req.instructions ? req.instructions : 'None provided'}
                </div>
            </div>
        `;
        
        console.log("Attempting to open modal...");
        modalElement.classList.add('open');
    }

    function closeModal() {
        document.getElementById('detailsModal').classList.remove('open');
    }

    // RENDER TABLE
    function renderTable() {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';
        
        if (requests.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No data found.</td></tr>';
            return;
        }

        requests.forEach(req => {
            const tr = document.createElement('tr');
            const locDisplay = req.location.length > 25 ? req.location.substring(0, 25) + '...' : req.location;

            tr.innerHTML = `
                <td><strong>${req.request_id}</strong></td>
                <td>
                    ${req.event_date}<br>
                    <small style="color:#888">${req.event_time}</small>
                </td>
                <td>
                    ${req.customer_name}<br>
                    <small>${req.contact_number}</small>
                </td>
                <td title="${req.location}">${locDisplay}</td>
                <td>${req.num_people}</td>
                <td><span class="status ${req.status}">${req.status}</span></td>
                <td class="actions">
                    <button class="btn-view" onclick="viewDetails(${req.id})" title="View Details">👁️</button>
                    ${req.status === 'Pending' ? `
                        <button class="btn-approve" onclick="updateStatus(${req.id}, 'Approved')" title="Approve">✓</button>
                        <button class="btn-reject" onclick="updateStatus(${req.id}, 'Rejected')" title="Reject">✕</button>
                    ` : ''}
                    <button class="btn-delete" onclick="deleteRequest(${req.id})" title="Delete Record">🗑️</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function updateStats() {
        document.getElementById('count-total').innerText = requests.length;
        document.getElementById('count-pending').innerText = requests.filter(r => r.status === 'Pending').length;
        document.getElementById('count-approved').innerText = requests.filter(r => r.status === 'Approved').length;
    }

    function showToast(msg) {
        const x = document.getElementById("toast");
        x.innerText = msg;
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    }

    // Initial Load
    loadData();
</script>

</body>
</html>