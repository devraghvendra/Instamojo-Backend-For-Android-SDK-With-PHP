# Instamojo-Backend-For-Android-SDK

A simple code snippet which allow back-end to generate order-id to proceed payment on android or ios  sdk. 

# Send following data in form-data through postman.

phone:9918603117
name:Raghvendra
email:raghvendrapathak143@gmail.com
amount:100

# Add your client_id and client_sercret key on payload array.

$payload = array(
    'grant_type' => 'client_credentials',
    'client_id' => 'XXX',
    'client_secret' => 'XXX',
);
