<?php

$servername = "localhost";
$username = "";
$password = "";
$dbname = "";


$citiesArray = array();

$url = "http://egyptexpress.me:1929/EGEXPService.svc/CityList";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
   "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = <<<DATA
{
  "UserName":"",
  "Password":""
  
}
DATA;


curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

     $result = curl_exec($curl);
     $cities = json_decode($result, true);
     $cityList = $cities['CityListLocation'];
        foreach($cityList as $city){
          //  $i++;
            $code = $city['CityCode'];
            $name = $city['CityName'];
            $cityArray = array(
                'value' => $code,
                'label' => $name
            );
            array_push($citiesArray,$cityArray);
        }

        //echo '<pre>';print_r($citiesArray);die;
       // echo $i;
        print_r($citiesArray) ;
        
        
                // Create connection
          $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
         if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
            }
        
        
          foreach($citiesArray as $city)
          
           {
               
           
           $code = $city['value'];
           $name = $city['label'];
        
        
          $sql1 = "INSERT INTO directory_country_region ( country_id, code,default_name)
                  VALUES ( 'EG', '$code', '$name')";
        
        
        
          if (mysqli_query($conn, $sql1)) {
              $last_id = mysqli_insert_id($conn);
              
              $sql2 = "INSERT INTO directory_country_region_name ( locale, region_id,name)
                  VALUES ( 'en_US', '$last_id' , '$name')";
                  
                  mysqli_query($conn, $sql2);
              
              
              echo "New record created successfully. Last inserted ID is: " . $last_id;
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        
        
        
        
        }
        
        mysqli_close($conn);



curl_close($curl);

