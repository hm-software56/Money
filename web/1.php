<?PHP

function sendMessage()
{
    $content = array(
        "en" => 'Intelligent Delivery takes the times your users have visited your site or app and sends the notification based on the most approximate time the user will open the app again'
    );

    $fields = array(
        'app_id' => "8611a545-6f5f-4e15-9e3a-b992ae4c6cac",
        //'included_segments' => array('All'),
		'include_player_ids' => array("17ad4f7c-8094-4107-90de-15394d70b0dc"),
        'data' => array("foo" => "bar"),
        'contents' => $content,
        //'small_icon' => "resources/android/icon/drawable-xxxhdpi-icon.png",
        // 'large_icon' => "resources/android/icon/drawable-xxxhdpi-icon.png",
    );

    $fields = json_encode($fields);
    print("\nJSON sent:\n");
    print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic ZjZjZjdmYjAtZTY1MC00NGQ4LWFlNDItNTQ4NzIwMGMyM2U0'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
$response = sendMessage();
$return["allresponses"] = $response;
$return = json_encode($return);

print("\n\nJSON received:\n");
print($return);
print("\n");

?>