<?php
error_reporting(0);
include "koneksi.php";
function anti_injection($data){
  $filter = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}

$username = anti_injection($_POST['user']);
$pass     = anti_injection(md5($_POST['password']));

// pastikan username dan password adalah berupa huruf atau angka.
if (!ctype_alnum($username) OR !ctype_alnum($pass)){?>
<script type="text/javascript">
 alert("Maaf,Username atau Password yang anda Input Salah");
history.back();
</script>
<?php
}
else{
$login=mysql_query("SELECT * FROM user WHERE username='$username' AND password='$pass'");
$ketemu=mysql_num_rows($login);
$r=mysql_fetch_array($login);

// Apabila username dan password ditemukan
if ($ketemu > 0){
  session_start();

  $_SESSION[namauser]     = $r[username];
  $_SESSION[id_user]     = $r[id_user];
  $_SESSION[leveluser]    = $r[id_level];


 header('location:../admin/');
}
else{ ?>
<script type="text/javascript">
 alert("Maaf,Username atau Password yang anda Input Salah");
history.back();
</script>
<?php
}
}
?>
