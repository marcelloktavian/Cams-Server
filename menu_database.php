<?php require_once 'include/config.php' ?>
<?php

$where = " WHERE m.hide = 0 ";
$orderby = ' ORDER BY m.menu_id ';
$sql = "SELECT m.*,COALESCE(ga.policy,'') as ga_policy FROM menu m "
. "LEFT JOIN group_access ga ON m.menu_id = ga.menu_id AND ga.group_id = ".$_SESSION['user']['group_id']
.$where.$orderby;
     //   var_dump($_SESSION['user']);
       // var_dump($sql);
$q = $db->query($sql);

$data1 = $q->fetchAll(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM group_access WHERE group_id ='.$_SESSION['user']['group_id'];
// var_dump($sql);
$ga = $db->query($sql);

$list_group_access  = $ga->fetchAll(PDO::FETCH_ASSOC);

file_put_contents("GROUP_ACCESS_CACHE".$_SESSION['user']['group_id'], serialize($list_group_access));

		 // var_dump($list_group_access); 

$i=0;
$responce = '';
?>

<span class="tester"><h3>Main Menu</h3></span>
<div>
  <ul id="browser" class="filetree">
    <?php
    $menu_group = '';
    $dont_show_child_parent_id = 0;

    foreach($data1 as $line){ 
      $pos = strpos($line['ga_policy'],VIEW_POLICY);
      // var_dump($pos);
      $show = $pos !== false;

      if ($line['url']=="#") {
        if ($show==true){
          if ($menu_group==''){
            echo '<li><span class="folder">'.$line['menu_name'].'</span>
            <ul>';
            $menu_group=$line['menu_group'];
          }
          else if ($menu_group!=$line['menu_group']){
            echo '</ul>';
            echo '<li><span class="folder">'.$line['menu_name'].'</span>
            <ul>';
            $menu_group=$line['menu_group'];
          }
        }else {
         $dont_show_child_parent_id = $line['menu_id'];
       }
     } else {

      if (($show)&&($line['menu_parent']!=$dont_show_child_parent_id)){
        if ($line['span_id']=='IMPORT') {
          $import = "<li><a href='javascript: void(0)' onclick=".
          'window.open('.
          "'".BASE_URL.$line['url']."');".
          ">".$line['menu_name']."</a></li>";
          echo $import;
        } else {
         echo '<li><span class="file" id="'.$line['span_id'].'" rel="'.BASE_URL.$line['url'].'">'.$line['menu_name'].'</span></li>';
       }
     }
   }
 }
 echo "</ul>";



 ?>
 </div>


 <span class="tester"><h3>Setting</h3></span>
 <div>

 <ul id="browser2" class="filetree">
  <!-- <li><span class="folder">Toko</span>
 <ul>
 <li><span class="file" style="cursor: pointer" id="statustoko" rel="<?php echo BASE_URL ?>pages/statustoko.php">Status Toko</span></li>

 </ul>
 </li> -->

 <li><span class="folder">Profile</span>
 <ul>
 <li><span class="file" style="cursor: pointer" id="ganti_password" rel="<?php echo BASE_URL ?>pages/change_password.php">Change Password</span></li>

 <li><span class="logout" rel=""><a href="<?php echo BASE_URL ?>logout.php">Logout</a></span></li>

 </ul>
 </li>												
 </ul>
 </div>