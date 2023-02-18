<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        error_reporting(0);
        $action = strtoupper($_GET['action']);
        echo $action .' Data Pengguna';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="dataUser_Form" method="post" action="<?php echo BASE_URL ?>pages/setting/dataUser.php?action=process" class="ui-helper-clearfix">
            <?php
            if(strtolower($_GET['action']) == 'edit') {
                echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="user_id" name="user_id">';
                $select = $db->prepare('SELECT du.user_id, du.username,du.nama, du.password,du.email,du.alamat, g.id AS groupid, g.nama AS ROLE FROM `user` du INNER JOIN `group` g ON g.id=du.group_id WHERE du.user_id= :id');
                $select->execute(array(':id' => $_GET['id']));
                $row = $select->fetch(PDO::FETCH_ASSOC);
            }

            $query1 = $db->query("SELECT * from `group`");
            $group = $query1->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <!-- Nama -->
            <label for="nama" class="ui-helper-reset label-control">Nama :</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['nama']) ? $row['nama'] : ''; ?>" type="text" class="required" id="nama" name="nama">
            </div>

            <!-- Username -->
            <label for="username" class="ui-helper-reset label-control">Username :</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['username']) ? $row['username'] : ''; ?>" type="text" class="required" id="username" name="username">
            </div>

            <?php
            if(strtolower($_GET['action']) == 'add') {
                ?>
                <!-- Password -->
                <label for="password" class="ui-helper-reset label-control">Password :</label>
                <div class="ui-corner-all form-control">
                    <input value="" type="password" class="required" id="password" name="password">
                </div>
            <?php } ?>

            <!-- Email -->
            <label for="email" class="ui-helper-reset label-control">Email :</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" type="text" class="required" id="email" name="email">
            </div>

            <!-- Posisi -->
            <label for="posisi" class="ui-helper-reset label-control">Posisi :</label>
            <div class="ui-corner-all form-control">
                <select name="posisi" id="posisi">
                    <option value="<?php echo isset($row['groupid']) ? $row['groupid'] : ''; ?>"><?php echo isset($row['ROLE']) ? $row['ROLE'] : ''; ?></option>
                    <?php 
                    foreach($group as $group1){
                        if ($group1['nama'] != $row['ROLE']) {
                           echo "<option value=".$group1['id'].">".$group1['nama']."</option>";
                       }
                   }
                   ?>
               </select>
           </div>

           <!-- Alamat -->
           <label for="alamat" class="ui-helper-reset label-control">Alamat :</label>
           <div class="ui-corner-all form-control">
            <textarea name="alamat" id="note" cols="30" rows="10"><?php echo isset($row['alamat']) ? $row['alamat'] : ''; ?></textarea>
        </div>

    </form>
</div>
</div>

