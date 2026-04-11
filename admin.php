<?php
session_start();
require_once 'config/db.php';

// --- SECURITY CHECK ---
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f7f9fc; margin: 0; padding: 0; }
        
        /* HEADER */
        header { background: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); z-index: 10; position: relative; }
        h1 { margin: 0; color: #2d3436; font-size: 1.5rem; }
        .btn-refresh { background: #0984e3; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; }
        
        /* SUB-NAVIGATION (TABS) */
        .admin-sub-nav { background: white; border-bottom: 1px solid #eee; padding: 0 30px; display: flex; gap: 20px; }
        .nav-tab { padding: 15px 0; cursor: pointer; color: #636e72; font-weight: 500; border-bottom: 3px solid transparent; transition: 0.3s; }
        .nav-tab:hover { color: #d63031; }
        .nav-tab.active { color: #d63031; border-bottom-color: #d63031; }

        /* MAIN CONTAINER */
        .container { padding: 30px; max-width: 1200px; margin: 0 auto; }
        
        /* VIEW TOGGLING */
        .view-section { display: none; animation: fadeIn 0.3s; }
        .view-section.active-view { display: block; }

        /* STATS CARDS */
        .stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; }
        .stat-num { font-size: 24px; font-weight: bold; color: #ff6b6b; }
        
        /* TABLES */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 30px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #f1f1f1; }
        th { background: #f8f9fa; color: #636e72; font-weight: 600; }
        .status { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status.Pending { background: #fff3cd; color: #856404; }
        .status.Approved { background: #d4edda; color: #155724; }
        .status.Rejected { background: #f8d7da; color: #721c24; }
        
        /* ACTION BUTTONS */
        .actions button { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px; color: white; font-size: 14px; }
        .btn-view { background: #74b9ff; } 
        .btn-approve { background: #00b894; }
        .btn-reject { background: #ff7675; }
        .btn-delete { background: #636e72; } 

        /* MODAL STYLES */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 2000; }
        .modal-overlay.open { display: flex; }
        .modal-content { background: white; padding: 25px; border-radius: 12px; width: 90%; max-width: 600px; position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto; }
        .modal-close { position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 24px; cursor: pointer; color: #aaa; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #eee; padding-bottom: 5px; }
        .detail-label { font-weight: 600; color: #636e72; width: 40%; }
        .menu-list-container { margin-top: 15px; background: #fdfbf7; padding: 15px; border-radius: 6px; border: 1px solid #eee; }
        .menu-list-ul { padding-left: 20px; margin: 0; column-count: 2; }
        .menu-list-ul li { font-size: 0.9rem; margin-bottom: 5px; }

        /* TOAST */
        #toast { visibility: hidden; min-width: 250px; background-color: #333; color: #fff; text-align: center; border-radius: 4px; padding: 16px; position: fixed; z-index: 3000; bottom: 30px; right: 30px; }
        #toast.show { visibility: visible; animation: fadein 0.5s, fadeout 0.5s 2.5s; }
        @keyframes fadein { from {bottom: 0; opacity: 0;} to {bottom: 30px; opacity: 1;} }
        @keyframes fadeout { from {bottom: 30px; opacity: 1;} to {bottom: 0; opacity: 0;} }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body>

<header>
    <div style="display: flex; align-items: center; gap: 15px;">
        <h1>🍽️ Admin Dashboard</h1>
        <span style="font-size: 0.8rem; color: #636e72; background: #f1f2f6; padding: 5px 10px; border-radius: 4px;">
            <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Guest'); ?>
        </span>
    </div>
    
    <div style="display: flex; gap: 10px;">
        <button class="btn-refresh" onclick="loadData()">Refresh Data</button>
        <a href="logout.php" style="text-decoration: none; padding: 8px 16px; background: #ff7675; color: white; border-radius: 6px; font-weight: 500;">Logout</a>
    </div>
</header>

<!-- NEW SUB NAVIGATION -->
<div class="admin-sub-nav">
    <div class="nav-tab active" onclick="switchAdminView('requests')" id="tab-requests">📋 Manage Requests</div>
    <div class="nav-tab" onclick="switchAdminView('feedbacks')" id="tab-feedbacks">⭐ View Feedbacks</div>
</div>

<main class="container">

    <!-- VIEW 1: REQUESTS (Default) -->
    <div id="view-requests" class="view-section active-view">
        <!-- Stats -->
        <div class="stats">
            <div class="stat-card"><div class="stat-num" id="count-total">0</div><div>Total Requests</div></div>
            <div class="stat-card"><div class="stat-num" id="count-pending" style="color:#fdcb6e">0</div><div>Pending</div></div>
            <div class="stat-card"><div class="stat-num" id="count-approved" style="color:#00b894">0</div><div>Approved</div></div>
        </div>

        <!-- Requests Table -->
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
            <tbody id="tableBody"></tbody>
        </table>
    </div>

    <!-- VIEW 2: FEEDBACKS (Hidden by default) -->
    <div id="view-feedbacks" class="view-section">
        <h2 style="margin-bottom: 20px; color: #2d3436;">Customer Reviews</h2>
        <table id="feedbackTable">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Rating</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="feedbackBody">
                <!-- Feedbacks load here -->
            </tbody>
        </table>
    </div>

</main>

<!-- DETAILS MODAL -->
<div class="modal-overlay" id="detailsModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <div id="modalBody"></div>
    </div>
</div>

<!-- Toast -->
<div id="toast">Message</div>

<script>
    let requests = [];

    // --- NAVIGATION LOGIC ---
    function switchAdminView(viewName) {
        // Hide all views
        document.querySelectorAll('.view-section').forEach(el => el.classList.remove('active-view'));
        document.querySelectorAll('.nav-tab').forEach(el => el.classList.remove('active'));

        // Show selected view
        document.getElementById(`view-${viewName}`).classList.add('active-view');
        document.getElementById(`tab-${viewName}`).classList.add('active');

        // Load data specifically for that view
        if(viewName === 'requests') {
            loadData();
        } else if (viewName === 'feedbacks') {
            loadFeedbacks();
        }
    }

    // --- REQUESTS LOGIC ---
    function loadData() {
        fetch('api/read.php')
            .then(res => res.json())
            .then(data => {
                requests = data;
                renderTable();
                updateStats();
            })
            .catch(err => showToast("Error loading data"));
    }

    function renderTable() {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';
        if (requests.length === 0) { tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No data found.</td></tr>'; return; }

        requests.forEach(req => {
            const tr = document.createElement('tr');
            const locDisplay = req.location.length > 25 ? req.location.substring(0, 25) + '...' : req.location;
            tr.innerHTML = `
                <td><strong>${req.request_id}</strong></td>
                <td>${req.event_date}<br><small style="color:#888">${req.event_time}</small></td>
                <td>${req.customer_name}<br><small>${req.contact_number}</small></td>
                <td title="${req.location}">${locDisplay}</td>
                <td>${req.num_people}</td>
                <td><span class="status ${req.status}">${req.status}</span></td>
                <td class="actions">
                    <button class="btn-view" onclick="viewDetails(${req.id})">👁️</button>
                    ${req.status === 'Pending' ? `
                        <button class="btn-approve" onclick="updateStatus(${req.id}, 'Approved')">✓</button>
                        <button class="btn-reject" onclick="updateStatus(${req.id}, 'Rejected')">✕</button>
                    ` : ''}
                    <button class="btn-delete" onclick="deleteRequest(${req.id})">🗑️</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function updateStatus(id, status) {
        if(!confirm(`Mark as ${status}?`)) return;
        fetch('api/update.php', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({ id: id, status: status }) })
        .then(res => res.json()).then(result => { showToast(result.status === 'success' ? "Updated" : "Error"); loadData(); });
    }

    function deleteRequest(id) {
        if(!confirm("Delete this record?")) return;
        fetch('api/delete.php', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({ id: id }) })
        .then(res => res.json()).then(result => { showToast(result.status === 'success' ? "Deleted" : "Error"); loadData(); });
    }

    function updateStats() {
        document.getElementById('count-total').innerText = requests.length;
        document.getElementById('count-pending').innerText = requests.filter(r => r.status === 'Pending').length;
        document.getElementById('count-approved').innerText = requests.filter(r => r.status === 'Approved').length;
    }

    // --- FEEDBACKS LOGIC ---
    function loadFeedbacks() {
        fetch('api/read_feedbacks.php')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('feedbackBody');
                tbody.innerHTML = '';
                if(data.length === 0) { tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">No feedback yet.</td></tr>'; return; }

                data.forEach(fb => {
                    let stars = ''; for(let i=0; i<5; i++) stars += i < fb.rating ? '★' : '☆';
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td><strong>${fb.customer_name}</strong></td>
                        <td style="color: #f1c40f; letter-spacing: 2px;">${stars}</td>
                        <td style="font-style: italic;">${fb.message}</td>
                        <td style="font-size: 0.8rem; color: #999;">${new Date(fb.created_at).toLocaleDateString()}</td>
                    `;
                    tbody.appendChild(tr);
                });
            });
    }

    // --- MODAL & DETAILS (Same as before) ---
    function viewDetails(id) {
        const req = requests.find(r => r.id == id);
        if (!req) return;
        let selectedItems = [];
        try { selectedItems = JSON.parse(req.selected_menu || '[]'); } catch(e) {}
        
        let menuHtml = '';
        if (selectedItems.length > 0) {
            menuHtml = '<div class="menu-list-container"><h4 style="margin-bottom: 10px; color: #d63031;">🍽️ Selected Menu Items:</h4><ul class="menu-list-ul">';
            selectedItems.forEach(item => { menuHtml += `<li>• ${item}</li>`; });
            menuHtml += '</ul></div>';
        } else { menuHtml = '<p style="color:#999; font-style:italic; font-size: 0.9rem;">No specific menu items selected.</p>'; }

        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="margin:0; color: #2d3436; border-bottom: 2px solid #ff6b6b; padding-bottom: 10px;">Full Request Details</h3>
            </div>
            <div class="detail-row"><span class="detail-label">ID:</span> <span>${req.request_id}</span></div>
            <div class="detail-row"><span class="detail-label">Name:</span> <span>${req.customer_name}</span></div>
            <div class="detail-row"><span class="detail-label">Contact:</span> <span>${req.contact_number}</span></div>
            <div class="detail-row"><span class="detail-label">Date:</span> <span>${req.event_date} at ${req.event_time}</span></div>
            <div class="detail-row"><span class="detail-label">Location:</span> <span>${req.location}</span></div>
            <div class="detail-row"><span class="detail-label">Type:</span> <span>${req.event_type}</span></div>
            <div class="detail-row"><span class="detail-label">Guests:</span> <span>${req.num_people}</span></div>
            <div class="detail-row"><span class="detail-label">Status:</span> <span class="status ${req.status}">${req.status}</span></div>
            ${menuHtml}
            <div style="margin-top: 15px;"><span class="detail-label" style="display:block; margin-bottom:5px;">Notes:</span><div style="background: #f1f2f6; padding: 10px; border-radius: 6px; font-size: 14px;">${req.instructions || 'None'}</div></div>
        `;
        document.getElementById('detailsModal').classList.add('open');
    }

    function closeModal() { document.getElementById('detailsModal').classList.remove('open'); }
    function showToast(msg) { const x = document.getElementById("toast"); x.innerText = msg; x.className = "show"; setTimeout(() => { x.className = x.className.replace("show", ""); }, 3000); }

    // Load default view on start
    loadData();
</script>

</body>
</html>