<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catering Request</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f7f9fc; margin: 0; padding: 0; display: flex; justify-content: center; padding-top: 50px; }
        .container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 600px; }
        h2 { color: #2d3436; margin-top: 0; }
        .veg-note { background: #e6fffa; color: #00b894; padding: 10px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #636e72; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        button { width: 100%; padding: 14px; background: #ff6b6b; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; font-weight: 600; transition: background 0.3s; }
        button:hover { background: #fa5252; }
        #toast { visibility: hidden; min-width: 250px; background-color: #333; color: #fff; text-align: center; border-radius: 4px; padding: 16px; position: fixed; z-index: 1; bottom: 30px; right: 30px; }
        #toast.show { visibility: visible; animation: fadein 0.5s, fadeout 0.5s 2.5s; }
        @keyframes fadein { from {bottom: 0; opacity: 0;} to {bottom: 30px; opacity: 1;} }
        @keyframes fadeout { from {bottom: 30px; opacity: 1;} to {bottom: 0; opacity: 0;} }
    </style>
</head>
<body>

<div class="container">
    <h2>🍽️ Submit Catering Request</h2>
    <div class="veg-note">🌿 Note: We serve strictly vegetarian cuisine.</div>
    
    <form id="cateringForm">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" id="name" required>
        </div>
        <div class="form-group">
            <label>Contact Number (+ Country Code)</label>
            <input type="tel" id="contact" placeholder="+1 555-..." required>
        </div>
        <div style="display: flex; gap: 10px;">
            <div class="form-group" style="flex:1;">
                <label>Event Date</label>
                <input type="date" id="date" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Event Time</label>
                <input type="time" id="time" required>
            </div>
        </div>
        <div class="form-group">
            <label>Location / Address</label>
            <input type="text" id="location" placeholder="123 Main St..." required>
        </div>
        <div class="form-group">
            <label>Event Type</label>
            <select id="type" required>
                <option value="">Select...</option>
                <option value="Wedding">Wedding</option>
                <option value="Birthday">Birthday</option>
                <option value="Corporate">Corporate</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label>Number of People</label>
            <input type="number" id="people" min="1" required>
        </div>
        <div class="form-group">
            <label>Special Instructions</label>
            <textarea id="instructions" rows="3"></textarea>
        </div>
        <button type="submit">Submit Request</button>
    </form>
</div>

<div id="toast">Message here</div>

<script>
    const form = document.getElementById('cateringForm');
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = form.querySelector('button');
        btn.innerText = "Submitting...";
        btn.disabled = true;

        const data = {
            name: document.getElementById('name').value,
            contact: document.getElementById('contact').value,
            date: document.getElementById('date').value,
            time: document.getElementById('time').value,
            location: document.getElementById('location').value,
            type: document.getElementById('type').value,
            people: document.getElementById('people').value,
            instructions: document.getElementById('instructions').value
        };

        try {
            const response = await fetch('./api/create.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            
            showToast(result.status === 'success' ? `Succe  ss! ID: ${result.id}` : "Error submitting");
            if(result.status === 'success') form.reset();
        } catch (err) {
            console.log(err)
            showToast("Server Error");
        } finally {
            btn.innerText = "Submit Request";
            btn.disabled = false;
        }
    });

    function showToast(msg) {
        const x = document.getElementById("toast");
        x.innerText = msg;
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    }

    // Set min date
    document.getElementById('date').min = new Date().toISOString().split("T")[0];
</script>

</body>
</html>