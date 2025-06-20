<?php
function generateReferralLink($userId) {
    $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/register.php?ref=";
    $uniqueCode = base64_encode($userId . time());
    $referralLink = $baseUrl . $uniqueCode;
    
    // Update user's referral link in database
    global $conn;
    $sql = "UPDATE users SET referral_link = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $referralLink, $userId);
    $stmt->execute();
    
    return $referralLink;
}
?>
