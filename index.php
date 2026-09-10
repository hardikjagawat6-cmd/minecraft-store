<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minecraft Server Rank Store</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f4f4; text-align: center; }
        .store-box { background: white; padding: 25px; border-radius: 8px; display: inline-block; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); text-align: left; max-width: 90%; width: 400px; }
        input, select, button { width: 100%; padding: 12px; margin: 10px 0 20px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px; }
        button { background-color: #4CAF50; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>

    <h1>Minecraft Rank Store</h1>
    <p>Select your tier and purchase your rank instantly.</p>

    <div class="store-box">
        <form action="checkout.php" method="POST">
            <label for="username">Minecraft Username:</label>
            <input type="text" id="username" name="username" placeholder="e.g., Notch" required>
            
            <label for="rank">Choose a Rank:</label>
            <select id="rank" name="rank" required>
                <option value="King">King Rank - ₹500</option>
                <option value="God">God Rank - ₹400</option>
                <option value="vip">vip Rank - ₹300</option>
                <option value="Deadliest">Deadliest Rank - ₹200</option>
            </select>

            <button type="submit">Proceed to Payment</button>
        </form>
    </div>

</body>
</html>
