<?php

// --- CONFIGURATION SETUP ---
// 1. Paste your copied Discord Webhook URL between the single quotes below
$discord_webhook_url = 'https://discord.com/api/webhooks/1547260904247136266/p8tilxmLNr2snRPFpt77fOf8ronStYzBnRF-SBia7qXNjxR_KU87qFwN5FIpRfjCh0fk';

// 2. Paste your Kotak 811 UPI ID here (e.g., "9876543210@kotak" or "yourname@kotak")
$your_upi_id = "PASTE_YOUR_KOTAK_UPI_ID_HERE"; 

// 3. Your target discord account details for the notification log
$target_admin = "honeyisone";
// ----------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player = htmlspecialchars($_POST['username']);
    $rank = htmlspecialchars($_POST['rank']);
    $status = "Awaiting Verification";

    // Price mapped perfectly to your tiers
    $price = 0;
    if ($rank == "King") $price = 500;
    if ($rank == "God") $price = 400;
    if ($rank == "vip") $price = 300;
    if ($rank == "Deadliest") $price = 200;

    // Generate plain string structure for instant UPI payment app hooks
    $upi_text = "upi://pay?pa=" . urlencode($your_upi_id) . "&pn=MinecraftStore&am=" . $price . "&cu=INR&tn=" . urlencode("Rank_" . $rank . "_For_" . $player);

    // Reliable Cloud Image Render (Works perfectly on Render)
    $qr_image_url = "https://qrserver.com" . urlencode($upi_text);

    // --- DISCORD WEBHOOK LOGIC ---
    $hook_data = [
        "username" => "Shop Alert Bot",
        "embeds" => [[
            "title" => "🚨 New Pending Purchase Received!",
            "color" => 3066993, 
            "fields" => [
                ["name" => "Player Username", "value" => $player, "inline" => true],
                ["name" => "Selected Rank", "value" => $rank, "inline" => true],
                ["name" => "Price Paid", "value" => "₹" . $price, "inline" => true],
                ["name" => "Order Status", "value" => $status, "inline" => false],
                ["name" => "Attention Admin Account", "value" => "@" . $target_admin, "inline" => false]
            ],
            "footer" => [
                "text" => "Minecraft Web Store Logs"
            ],
            "timestamp" => date('c')
        ]]
    ];

    // Fire off the background CURL request directly to Discord
    $ch = curl_init($discord_webhook_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($hook_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // --- DISPLAY SCREEN RENDER ---
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Scan to Pay</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 30px; background-color: #f4f4f4; }
            .invoice-box { background: white; padding: 30px; display: inline-block; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 90%; width: 400px; }
            .qr-container { margin: 20px auto; padding: 10px; background: white; display: inline-block; border: 4px solid #333; border-radius: 4px; }
            .alert-msg { color: #555; font-size: 14px; background: #fff3cd; padding: 10px; border-radius: 4px; margin-top: 15px; }
        </style>
    </head>
    <body>
        <div class="invoice-box">
            <h2>Order Created Successfully!</h2>
            <p>Player: <strong><?php echo $player; ?></strong></p>
            <p>Item Tier: <strong><?php echo $rank; ?></strong></p>
            <p>Total Cost: <strong>₹<?php echo $price; ?></strong></p>
            
            <p>Scan this QR code using any UPI App (BHIM, PhonePe, Paytm, GooglePay) to complete payment:</p>
            
            <!-- Display your personal static QR code image -->
<img src="my-qr.png" alt="Payment QR Code" style="width: 250px; height: 250px; display: block; margin: 20px auto;">
            
            <div class="alert-msg">
                ⚠️ After payment finishes, please wait. Staff profile <strong>@<?php echo $target_admin; ?></strong> has been pinged on Discord to verify your transaction!
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
  
