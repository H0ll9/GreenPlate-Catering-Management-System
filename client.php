<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catering Request</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f7f9fc; margin: 0; padding: 0; display: flex; justify-content: center; padding-top: 50px; padding-bottom: 50px; }
        .container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 800px; }
        h2 { color: #2d3436; margin-top: 0; }
        .veg-note { background: #e6fffa; color: #00b894; padding: 10px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; }
        
        .form-group { margin-bottom: 15px; }
        .form-row { display: flex; gap: 10px; }
        
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #636e72; font-size: 0.9rem; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 6px; box-sizing: border-box; font-family: inherit; font-size: 14px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #d63031; }
        
        button { width: 100%; padding: 14px; background: #d63031; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; font-weight: 600; transition: background 0.3s; }
        button:hover { background: #b71540; }

        /* Menu Styling */
        .menu-section { background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e1e1e1; margin-bottom: 20px; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 8px; padding-top : 15px;}
        .menu-item label { display: flex; align-items: center; cursor: pointer; font-size: 13px; color: #2d3436; }
        .menu-item input { width: auto; margin-right: 8px; }
        
        .category-title { margin-top: 15px; color: #d63031; border-bottom: 1px solid #ddd; padding-bottom: 5px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <h2>🍽️ Submit Catering Request</h2>
    <div class="veg-note">🌿 <strong>Note:</strong> This service provides strictly vegetarian cuisine.</div>
    
    <form id="cateringForm">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" id="customerName" placeholder="e.g. John Doe" required>
        </div>

        <div class="form-row">
            <div class="form-group" style="flex:1;">
                <label>Contact Number</label>
                <input type="tel" id="contactNumber" placeholder="+91 9876543210" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Event Date</label>
                <input type="date" id="eventDate" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group" style="flex:1;">
                <label>Event Time</label>
                <input type="time" id="eventTime" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Event Type</label>
                <select id="eventType" required>
                    <option value="">Select...</option>
                    <option value="Wedding">Wedding</option>
                    <option value="Birthday">Birthday</option>
                    <option value="Corporate">Corporate</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Location / Address</label>
            <input type="text" id="eventLocation" placeholder="Mangalore, Karnataka" required>
        </div>

        <div class="form-group">
            <label>Number of People</label>
            <input type="number" id="numPeople" min="1" placeholder="e.g. 50" required>
        </div>

        <!-- MENU SECTION -->
        <div class="menu-section">
            <h3 style="margin:0 0 15px 0; color: #2d3436; font-size: 16px;">Select Menu Items</h3>
            <div id="menuContainer">
                <!-- JS will populate this -->
            </div>
        </div>

        <div class="form-group">
            <label>Special Instructions</label>
            <textarea id="instructions" rows="3"></textarea>
        </div>

        <button type="submit">Submit Request</button>
    </form>
</div>

<script>
    // 1. MENU DATA
    const menuData = [
        { category: "🥗 Starters", items: ["Paneer Tikka", "Veg Spring Rolls", "Hara Bhara Kabab", "Gobi 65", "Mini Samosa"] },
        { category: "🍲 Soups", items: ["Tomato Soup", "Sweet Corn Veg Soup", "Hot & Sour", "Cream of Mushroom"] },
        { category: "🍛 Paneer Specials", items: ["Paneer Butter Masala", "Kadai Paneer", "Shahi Paneer"] },
        { category: "🍛 Veg Curries", items: ["Mixed Veg", "Aloo Gobi", "Veg Kolhapuri", "Dum Aloo"] },
        { category: "🍛 Dal", items: ["Dal Tadka", "Dal Makhani", "Yellow Dal"] },
        { category: "🍚 Rice", items: ["Steamed Rice", "Jeera Rice", "Veg Pulao", "Veg Biryani"] },
        { category: "🫓 Breads", items: ["Roti", "Butter Roti", "Naan", "Garlic Naan", "Paratha"] },
        { category: "🍝 Indo-Chinese", items: ["Fried Rice", "Hakka Noodles", "Manchurian", "Chilli Paneer"] },
        { category: "🍰 Desserts", items: ["Gulab Jamun", "Rasgulla", "Gajar Halwa", "Ice Cream"] },
        { category: "🥤 Drinks", items: ["Welcome Drink", "Lassi", "Buttermilk", "Tea/Coffee"] }
    ];

    // 2. RENDER MENU
    const menuContainer = document.getElementById('menuContainer');
    
    menuData.forEach(section => {
        const title = document.createElement('div');
        title.className = 'category-title';
        title.textContent = section.category;
        menuContainer.appendChild(title);

        const grid = document.createElement('div');
        grid.className = 'menu-grid';

        section.items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'menu-item';
            div.innerHTML = `<label><input type="checkbox" name="menu_items" value="${item}"> ${item}</label>`;
            grid.appendChild(div);
        });
        menuContainer.appendChild(grid);
    });

    // 3. SUBMIT HANDLER
    document.getElementById('cateringForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.querySelector('button[type="submit"]');
        btn.innerText = "Submitting...";
        btn.disabled = true;

        // Collect Data
        const checkedBoxes = document.querySelectorAll('input[name="menu_items"]:checked');
        const selectedMenu = Array.from(checkedBoxes).map(cb => cb.value);

        const data = {
            name: document.getElementById('customerName').value,
            contact: document.getElementById('contactNumber').value,
            date: document.getElementById('eventDate').value,
            time: document.getElementById('eventTime').value,
            location: document.getElementById('eventLocation').value,
            type: document.getElementById('eventType').value,
            people: document.getElementById('numPeople').value,
            instructions: document.getElementById('instructions').value,
            menu: selectedMenu // Sending menu array
        };

        try {
            const response = await fetch('api/create.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            
            alert(result.status === 'success' ? `Success! ID: ${result.id}` : "Error: " + result.message);
            if(result.status === 'success') document.getElementById('cateringForm').reset();
        } catch (err) {
            alert("Server Error. Check Console (F12) for details.");
            console.error(err);
        } finally {
            btn.innerText = "Submit Request";
            btn.disabled = false;
        }
    });

    // Set Min Date
    document.getElementById('eventDate').min = new Date().toISOString().split("T")[0];
</script>

</body>
</html> 