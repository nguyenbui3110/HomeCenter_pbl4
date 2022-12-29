

<?php 

include 'function/print-HTML.php';
include 'sql/sql-function.php';

$conn = ConnectDatabse();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>home center</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    
    <!-- JS -->
    <script type="text/javascript" src="js/jquery/jquery.js"></script>
    <script type="text/javascript" src="js/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="js/query.js"></script>
    
  </head>
  <body class="lazy-man">
  <iframe name="panel" height="1080px" width="100%" style="background-color: rgba(113, 177, 167, 0.957) ;" src="panel.php" frameborder="0"></iframe>
    <!-- Fixed navbar -->
    <div class="container"></div>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
        </div>
      </div>
    </nav>
    <!-- Conainer -->
    <div class="container">

      <div class="row">
        <div class="land-1">
<?php

	PrintObjectDatabase($conn);
?>
              <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
              <div class="object obj-button obj-send send">
                <div class="obj-info">
                  <div class="type-input" >
                    <label for="temp">nhiệt độ: </label>
                    <input type="text" name=temp id="NhietDo" readonly>
                  </div>
                  <div class="type-input">
                    <label for="humid">độ ẩm: </label>
                    <input type="text" name=humid id="DoAm" readonly>
                  </div>
                </div>
                
                <div class="obj-off"><i class="fa fa-close"></i></div>
                <div class="clearfix"></div>
              </div>
            </div>
          <div class="clearfix"></div>  
        </div>
      </div>
    </div>
	  
    <script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-database.js"></script>

    <!-- TODO: Add SDKs for Firebase products that you want to use
        https://firebase.google.com/docs/web/setup#available-libraries -->
    <script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-analytics.js"></script>

    <script>
      // Your web app's Firebase configuration
      // For Firebase JS SDK v7.20.0 and later, measurementId is optional
      const firebaseConfig = {
            apiKey: "AIzaSyCbbXVyhciaRIALWYZCrNiuA642aMe3w00",
            authDomain: "fir-d98dc.firebaseapp.com",
            databaseURL: "https://fir-d98dc-default-rtdb.firebaseio.com",
            projectId: "fir-d98dc",
            storageBucket: "fir-d98dc.appspot.com",
            messagingSenderId: "91193442748",
            appId: "1:91193442748:web:4d0f1a5dc426724141abb9",
            measurementId: "G-YDYD8N92K6"
          };
      // Initialize Firebase
      firebase.initializeApp(firebaseConfig);
      firebase.analytics();
          var temp = document.getElementById('NhietDo');
          temp.onload= function(){
            temp.value = "Loading...";
          }
          var dbRef = firebase.database().ref().child('Temp');
          dbRef.on('value', snap => temp.value = snap.val());
            var wet = document.getElementById('DoAm');
          wet.onload= function(){
            wet.value = "Loading...";
          }
          var dbRef2 = firebase.database().ref().child('Humidity');   
          dbRef2.on('value', snap => wet.value = snap.val());
    </script>
  </body>
</html>
<?php 
	CloseDatabase($conn);
?>
