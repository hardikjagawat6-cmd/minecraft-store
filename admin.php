<?php
// --- ADMIN CONFIGURATION ---
$admin_username = "admin";
$admin_password = "Honey=Hardik8959992974"; // Change this password immediately!

// Discord Webhook for approval logs (Same as your checkout webhook)
$discord_webhook_url = 'https://discord.com';
// ---------------------------

// Basic Mobile HTTP Authentication Guard
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $admin_username || $_SERVER['PHP_AUTH_PW'] !== $admin_password) {
    header('WWW-Authenticate: Basic realm="Admin Panel"');
    header('HTTP/1.0 401 Unauthorized');
    die("Access Denied. Invalid Admin Credentials.");
}

$message = "";

// Handle Approval Form Post Action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $player = htmlspecialchars($_POST['username']);
    $rank = htmlspecialchars($_POST['rank']);
    $action = $_POST['action']; // "Approve" or "Reject"
    
    $status_text = ($action === "approve") ? "✅ APPROVED & DELIVERED" : "❌ REJECTED / CANCELLED";
    $embed_color = ($action === "approve") ? 3066993 : 15158332; // Green or Red

    // Send Status Update Log back to Discord Channel
    $hook_data = [
        "username" => "Shop Management Bot",
        "embeds" => [[
            "title" => "📢 Order Status Updated by Staff!",
            "color" => $embed_color,
            "fields" => [
                ["name" => "Player Username", "value" => $player, "inline" => true],
                ["name" => "Rank Update", "value" => $rank, "inline" => true],
                ["name" => "New Status", "value" => $status_text, "inline" => false],
                ["name" => "Handled By", "value" => "@" . $_SERVER['PHP_AUTH_USER'], "inline" => false]
            ],
            "footer" => [ "text" => "Minecraft Web Store Administration" ],
            "timestamp" => date('c')
        ]]
    ];

    $ch = curl_init($discord_webhook_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($hook_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);

    $message = "<div class='alert success'>Order for <strong>$player</strong> has been successfully set to: $status_text</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 15px; margin: 0; text-align: center; }
        .admin-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 400px; margin: 20px auto; text-align: left; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; margin: 5px 0; border: none; border-radius: 4px; font-size: 16px; color: white; cursor: pointer; font-weight: bold; }
        .btn-approve { background-color: #2ecc71; }
        .btn-reject { background-color: #e74c3c; }
        .alert { padding: 12px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

    <h2>Staff Order Action Panel</h2>
    
    <div class="admin-box">
        <?php echo $message; ?>
        
        <form method="POST" action="admin.php">
            <label>Player Username:</label>
            <input type="text" name="username" placeholder="e.g., Yohoneyhoho" required>

            <label>Rank Ordered:</label>
            <select name="rank" required>
                <option value="King">King Rank</option>
                <option value="God">God Rank</option>
                <option value="vip">vip Rank</option>
                <option value="Deadliest">Deadliest Rank</option>
            </select>

            <button type="submit" name="action" value="approve" class="btn btn-approve">✅ Mark as Approved</button>
            <button type="submit" name="action" value="reject" class="btn btn-reject">❌ Reject Order</button>
        </form>
    </div>

</body>
</html>
