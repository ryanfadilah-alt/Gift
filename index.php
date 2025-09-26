<?php 
date_default_timezone_set('Asia/Jakarta'); 

// Handle POST message submission
if(isset($_POST['p'])){ 
    $message = trim($_POST['p']); 
    if(empty($message) || strlen($message) > 500){ 
        die('{"s":400,"error":"Invalid message length"}'); 
    } 
    $sanitized_message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); 
    $fp = fopen('messages.txt', 'a'); 
    if($fp){ 
        if(flock($fp, LOCK_EX)){ 
            if(fwrite($fp, '<div class="cp">Pesan :<br/>'.$sanitized_message.'<p>'.date("d-M-Y (H:i)").'</p></div>'.PHP_EOL) !== false){ 
                flock($fp, LOCK_UN); 
                fclose($fp); 
                die('{"s":200}'); 
            } else { 
                flock($fp, LOCK_UN); 
                fclose($fp); 
                die('{"s":500,"error":"Write failed"}'); 
            } 
        } else { 
            fclose($fp); 
            die('{"s":500,"error":"Lock failed"}'); 
        } 
    } else { 
        die('{"s":500,"error":"File open failed"}'); 
    } 
} 

// Handle POST data submission
if(isset($_POST['d'])){ 
    $data = trim($_POST['d']); 
    if(strlen($data) > 1000){ 
        die('{"s":400,"error":"Data too large"}'); 
    } 
    $sanitized_data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); 
    $fa = fopen('messages.txt', 'a'); 
    if($fa){ 
        if(flock($fa, LOCK_EX)){ 
            if(fwrite($fa,$sanitized_data) !== false){ 
                flock($fa, LOCK_UN); 
                fclose($fa); 
                die('{"s":200}'); 
            } else { 
                flock($fa, LOCK_UN); 
                fclose($fa); 
                die('{"s":500,"error":"Write failed"}'); 
            } 
        } else { 
            fclose($fa); 
            die('{"s":500,"error":"Lock failed"}'); 
        } 
    } else { 
        die('{"s":500,"error":"File open failed"}'); 
    } 
} 

// Handle GET data request
if(isset($_GET['d'])){ 
    $fa = fopen('messages.txt', 'a'); 
    if($fa) fclose($fa); 
    $fr = fopen('messages.txt', 'r'); 
    if($fr){ 
        if(flock($fr, LOCK_SH)){ 
            $content = fgets($fr); 
            flock($fr, LOCK_UN); 
            fclose($fr); 
            echo json_encode(array("d"=>$content)); 
        } else { 
            fclose($fr); 
            echo json_encode(array("d"=>"","error"=>"Lock failed")); 
        } 
    } else { 
        echo json_encode(array("d"=>"","error"=>"File not found")); 
    } 
    die; 
} 
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://dekatutorial.github.io/ct/s.js"></script>
</head>
<body>
<?php 
// Handle message display
if(isset($_GET['pesan'])){ 
    echo "<div id='ccp'>"; 
    $fp = fopen('messages.txt', 'r'); 
    if($fp){ 
        if(flock($fp, LOCK_SH)){ 
            fgets($fp); // Skip first line 
            while(!feof($fp)){ 
                echo fgets($fp); 
            } 
            flock($fp, LOCK_UN); 
            fclose($fp); 
        } else { 
            fclose($fp); 
            echo "<p>Lock failed.</p>"; 
        } 
    } else { 
        echo "<p>No messages found.</p>"; 
    } 
    die("</div></body></html>"); 
} 
?>
<script> 


teksHai = "Hai, ada surat buat alisha nih";
    
konten = [
  {
    gambar: "gambar1.HEIC",
    ucapan: "cie ada yang ulang tahun nih",
  },
  {
    gambar: "gambar2.HEIC",
    ucapan: "SELAMAT ULANG TAHUN YANG Ke-21 ya",
  },
  {
    gambar: "gambar3.HEIC",
    ucapan: "semoga panjang umur, sehat selalu, dan semangat kuliah semester terakhirnya",
  },
  {
    gambar: "gambar4.HEIC",
    ucapan: "🫶🏼",
  },
];

musik = "musik.mp3";
nomorWhatsapp = "6281364734728";

/*=========================*/
DekaTutorial(konten, musik, nomorWhatsapp);
</script>
</body>
</html>