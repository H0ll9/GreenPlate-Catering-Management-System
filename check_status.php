<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Request Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { 
             position: relative; height: 90vh; display: flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif; 
            background-color: #f7f9fc; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('./images/hero.avif'); 
            background-size: cover; background-position: center; color: var(--white);
        }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-top: 100px; }
        h2 { color: #2d3436; }
        .search-box { display: flex; gap: 10px; margin-bottom: 20px; }
        input { flex: 1; padding: 12px; border: 1px solid #dfe6e9; border-radius: 6px; font-size: 16px; }
        button { padding: 12px 20px; background: #0984e3; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
        button:hover { background: #74b9ff; }
        
        .request-card { border: 1px solid #eee; border-radius: 8px; padding: 15px; margin-bottom: 15px; position: relative; }
        .status-badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; color: white; }
        .status-Pending { background: #fdcb6e; }
        .status-Approved { background: #00b894; }
        .status-Rejected { background: #ff7675; }
        
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 14px; }
        .label { color: #636e72; font-weight: 500; }
        
        .hidden { display: none; }
        .box{
            
        }
    </style>
</head>
<body>

<div class="box">
    <div class="container">
        <h2>🔍 Check Request Status</h2>
        <p style="color: #636e72; margin-bottom: 20px;">Enter your phone number to view your catering requests.</p>
        
        <div class="search-box">
            <input type="text" id="phoneInput" placeholder="+9876543210">
            <button onclick="checkStatus()">Check</button>
        </div>

        <div id="resultsArea">
            <!-- Results appear here -->
        </div>
    </div>
</div>

<script>
    function checkStatus() {
        const phone = document.getElementById('phoneInput').value;
        const resultsArea = document.getElementById('resultsArea');
        
        if(!phone) {
            alert("Please enter a phone number");
            return;
        }

        resultsArea.innerHTML = '<p style="text-align:center; color: #999;">Loading...</p>';

        fetch(`./api/check.php?phone=${encodeURIComponent(phone)}`)
            .then(res => res.json())
            .then(data => {
                resultsArea.innerHTML = '';
                
                if (data.length === 0) {
                    resultsArea.innerHTML = '<p style="text-align:center; color: #ff7675;">No requests found for this number.</p>';
                    return;
                }

                data.forEach(req => {
                    const card = document.createElement('div');
                    card.className = 'request-card';
                    
                    card.innerHTML = `
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                            <h3 style="margin:0;">ID: ${req.request_id}</h3>
                            <span class="status-badge status-${req.status}">${req.status}</span>
                        </div>
                        <div class="detail-row"><span class="label">Date:</span> <span>${req.event_date}</span></div>
                        <div class="detail-row"><span class="label">Time:</span> <span>${req.event_time}</span></div>
                        <div class="detail-row"><span class="label">Event:</span> <span>${req.event_type}</span></div>
                        <div class="detail-row"><span class="label">Location:</span> <span>${req.location}</span></div>
                    `;
                    resultsArea.appendChild(card);
                });
            })
            .catch(err => {
                console.error(err);
                resultsArea.innerHTML = '<p style="text-align:center; color: #ff7675;">Error loading data.</p>';
            });
    }
</script>

</body>
</html>