<?php

function sendNotification($title, $body, $actionLink, $token)
{
    $url = "https://fcm.googleapis.com/fcm/send";

    $subscription_key = "key=AAAA3zQenjc:APA91bF0WsBUOmNJ_QE9f5ERx2B89fYgUfSLmNRWE0OaT40lx6VaGBGb63GhU8ilQ1BgxjiJhOgqr07Roor2-tCxuBnU0bP-RxstImDvKI9iNQ_adwFcjBI6xUqftIEcvIjaP9Ad8r2W";
    $request_headers = array(
        "Authorization:" . $subscription_key,
        "Content-Type: application/json",
    );
    $postRequest = [
        "notification" => [
            "title" => $title,
            "body" => $body,
            "icon" => "https://c.disquscdn.com/uploads/users/34896/2802/avatar92.jpg",
            "click_action" => $actionLink,
        ],
        /** Customer Token, As of now I got from console. You might need to pull from database */
        "to" => $token,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postRequest));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

    $season_data = curl_exec($ch);

    if (curl_errno($ch)) {
        print "Error: " . curl_error($ch);
        exit();
    }
    // Show me the result
    curl_close($ch);
    // print_r($season_data);
    return $season_data;
}

$username = $_POST['name'];
$n = $_POST['n'];
$title = "Registration Successful!";
$body = "Click here to view your login";
$actionLink = "view_login_details.php?n=" . $n;
sendNotification($title, $body, $actionLink, $_POST['token']);

$response = [
    "success" => "true",
    "data" => [
        "name" => $username,
        "username" => $username,
        "password" => $username,
    ],
];
echo json_encode($response);
