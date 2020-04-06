<?php 
include 'config.php';
if(!empty($_POST)){
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.instamojo.com/oauth2/token/');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $payload = array(
    'grant_type' => 'client_credentials',
    'client_id' => 'XXXXXXXXXX',
    'client_secret' => 'XXXXXXXXX',
);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    $response = curl_exec($ch);
    curl_close($ch);
    $decodedText = html_entity_decode($response);
    $myArray = array(json_decode($response, true));
    if(isset($myArray[0]['error'])){
        $result = array('status' => 500, 'message' => $myArray[0]['error']);
        die(json_encode($result));
    }else{
        $access_token = $myArray[0]["access_token"];
        if(!empty($access_token)){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.instamojo.com/v2/payment_requests/');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$access_token,
            ));
            $payload = array(
            'purpose' => 'Wonder Women',
            'phone' => $phone,
            'amount' => $amount,
            'buyer_name' => $name,
            'send_email' => true,
            'send_sms' => true,
            'email' => $email,
            'allow_repeated_payments' => false,
        );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);
            $decodedText = html_entity_decode($response);
            $myArray = array(json_decode($response, true));
            if(isset($myArray[0]['message'])){
                $result = array('status' => 500, 'message' => $myArray[0]['message']);
                die(json_encode($result));
            }else{
                $order_id = $myArray[0]["id"];
                if(!empty($order_id)){
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://api.instamojo.com/v2/gateway/orders/payment-request/');
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer '.$access_token,
                    ));
                        $payload = array(
                            'id' => $order_id
                        );
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $decodedText = html_entity_decode($response);
                        $myArray = array(json_decode($response, true));
                        $id = $myArray[0]["order_id"];
                        $result = array('status' => 200, 'message' => 'Success', 'order_id' =>$id);
                    }
            }
            }
    }
    }else{
        $result = array('status' => 500, 'message' => 'Missing Required Params');
    }
    die(json_encode($result));  
?>
