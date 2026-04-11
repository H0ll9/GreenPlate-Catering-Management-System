<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f7f9fc; margin: 0; padding: 20px; display: flex; justify-content: center; }
        .container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 600px; text-align: center; }
        h2 { color: #2d3436; }
        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #636e72; }
        input[type="text"], textarea { width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        textarea { height: 100px; resize: vertical; }
        
        /* Star Rating Styling */
        .rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 10px; }
        .rating input { display: none; }
        .rating label { font-size: 30px; color: #ddd; cursor: pointer; transition: color 0.2s; margin: 0; }
        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label { color: #f1c40f; }

        button { width: 100%; padding: 12px; background: #d63031; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; font-weight: 600; }
        button:hover { background: #b71540; }
        
        #toast { visibility: hidden; min-width: 250px; background-color: #2d3436; color: #fff; text-align: center; border-radius: 4px; padding: 16px; position: fixed; z-index: 1; bottom: 30px; right: 30px; }
        #toast.show { visibility: visible; animation: fadein 0.5s, fadeout 0.5s 2.5s; }
        @keyframes fadein { from {bottom: 0; opacity: 0;} to {bottom: 30px; opacity: 1;} }
        @keyframes fadeout { from {bottom: 30px; opacity: 1;} to {bottom: 0; opacity: 0;} }
    </style>
</head>
<body>

<div class="container">
    <h2>🌟 We Value Your Feedback</h2>
    <p style="color:#636e72; margin-bottom: 30px;">How was your experience with GreenPlate Catering?</p>
    
    <form id="feedbackForm">
        <div class="form-group">
            <label>Your Name</label>
            <input type="text" id="fName" required placeholder="John Doe">
        </div>

        <div class="form-group">
            <label>Rating</label>
            <div class="rating">
                <input type="radio" name="rating" id="star5" value="5"><label for="star5" title="Excellent">★</label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4" title="Good">★</label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3" title="Average">★</label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2" title="Poor">★</label>
                <input type="radio" name="rating" id="star1" value="1" required><label for="star1" title="Very Poor">★</label>
            </div>
        </div>

        <div class="form-group">
            <label>Your Message</label>
            <textarea id="fMessage" required placeholder="Tell us what you liked or how we can improve..."></textarea>
        </div>

        <button type="submit">Submit Feedback</button>
    </form>
    <br>
    <a href="index.php" style="color: #636e72; text-decoration: none; font-size: 0.9rem;">← Back to Home</a>
</div>

<div id="toast">Message</div>

<script>
    document.getElementById('feedbackForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Get Selected Rating
        const ratingInput = document.querySelector('input[name="rating"]:checked');
        if (!ratingInput) {
            alert("Please select a star rating.");
            return;
        }

        const data = {
            name: document.getElementById('fName').value,
            rating: ratingInput.value,
            message: document.getElementById('fMessage').value
        };

        try {
            const res = await fetch('api/submit_feedback.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            
            if (result.status === 'success') {
                showToast("Thank you! Feedback submitted.");
                document.getElementById('feedbackForm').reset();
            } else {
                alert("Error: " + result.message);
            }
        } catch (err) {
            alert("Server Error");
        }
    });

    function showToast(msg) {
        const x = document.getElementById("toast");
        x.innerText = msg;
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    }
</script>

</body>
</html>