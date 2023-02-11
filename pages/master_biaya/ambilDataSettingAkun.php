<?php

include("../../include/config.php");
    $rs = '';
   $i=0; $no=1;
    $query2 = $db->query("SELECT * FROM setting_akun");
    $rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    $count = $query2->rowCount();

    foreach($rows2 as $r2) {
        if ($i==0){
            $hidden = '	<input type="hidden" name="rowcount" value="'.$count.'">';
        }   
        $rs .= '<tr>';
        $rs .= '<td>'.$hidden.$r2['menu'].'<input type="hidden" name="menu_id'.$i.'" value="'.$r2['id'].'"></td>';
        if($r2['id']==5 || $r2['id']==6){
            $rs .= '<td align="center" colspan=2><select id="debet" name="debet'.$i.'" class="my-select" style="width:100%">';
            $rs .= '<option value="">-- Pilih Akun --</option>';
            $sql_products = 'SELECT a.* FROM `mst_coa` a  ';

            $query = '';
            $countnya = 0;

            $q = $db->query($sql_products.' where a.deleted=0 ORDER BY noakun ASC');
            $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
            foreach($data1 as $line) {
                if ($countnya == 0) {
                    $query .= "select id, noakun, nama, jenis from mst_coa where id='".$line['id']."' ";
                } else {
                    $query .= " UNION ALL select id, noakun, nama, jenis from mst_coa  where id='".$line['id']."' ";
                }
                $countnya++;
                $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
                $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
                foreach($data2 as $line2) {
                    $query .= " UNION ALL select id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ";
                }
                
            }

            $q2 = $db->query($query);
            $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
            foreach($data2 as $line2) {
                $selected = '';
                if($line2['noakun']==$r2['akun_debet']){$selected = "selected";}
                $rs .= "<option value='".$line2['noakun']."' $selected>".$line2['noakun']." - ".$line2['nama']."</option>";
            }
            $rs .= '</select>';
            $rs .='</td>';
        }else{
            $rs .= '<td align="center"><select id="debet" name="debet'.$i.'" class="my-select">';
            $rs .= '<option value="">-- Pilih Akun Debet --</option>';
            
            $sql_products = 'SELECT a.* FROM `mst_coa` a  ';

            $query = '';
            $countnya = 0;

            $q = $db->query($sql_products.' where a.deleted=0 AND a.jenis="Debet" ORDER BY noakun ASC');
            $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
            foreach($data1 as $line) {
                if ($countnya == 0) {
                    $query .= "select id, noakun, nama, jenis from mst_coa where id='".$line['id']."' ";
                } else {
                    $query .= " UNION ALL select id, noakun, nama, jenis from mst_coa  where id='".$line['id']."' ";
                }
                $countnya++;
                $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
                $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
                foreach($data2 as $line2) {
                    $query .= " UNION ALL select id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ";
                }
                
            }

            $q2 = $db->query($query);
            $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
            foreach($data2 as $line2) {
                $selected = '';
                if($line2['noakun']==$r2['akun_debet']){$selected = "selected";}
                $rs .= "<option value='".$line2['noakun']."' $selected>".$line2['noakun']." - ".$line2['nama']."</option>";
            }
            // $query = $db->query("SELECT det.* FROM det_coa det left join mst_coa coa on coa.id=det.id_parent where coa.jenis='Debet' ORDER BY noakun");
            // $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            // foreach($rows as $r) {
            //     $selected = '';
            //     if($r['id']==$r2['akun_debet']){$selected = "selected";}
            //     $rs .= '<option value="'.$r['id'].'" '.$selected.'>'.$r['noakun'].' - '.$r['nama'].'</option>';
            // }
            $rs .= '</select></td>';
            $rs .= '<td align="center"><select id="kredit" name="kredit'.$i.'" class="my-select">';
            $rs .= '<option value="">-- Pilih Akun Kredit --</option>';
            // $query = $db->query("SELECT det.* FROM det_coa det left join mst_coa coa on coa.id=det.id_parent where coa.jenis='Kredit' ORDER BY noakun");
            // $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            // foreach($rows as $r) {
            //     $selected = '';
            //     if($r['id']==$r2['akun_kredit']){$selected = "selected";}
            //     $rs .= '<option value="'.$r['id'].'" '.$selected.'>'.$r['noakun'].' - '.$r['nama'].'</option>';
            // }

            $sql_products = 'SELECT a.* FROM `mst_coa` a  ';

            $query = '';
            $countnya = 0;

            $q = $db->query($sql_products.' where a.deleted=0 AND a.jenis="Kredit" ORDER BY noakun ASC');
            $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
            foreach($data1 as $line) {
                if ($countnya == 0) {
                    $query .= "select id, noakun, nama, jenis from mst_coa where id='".$line['id']."' ";
                } else {
                    $query .= " UNION ALL select id, noakun, nama, jenis from mst_coa  where id='".$line['id']."' ";
                }
                $countnya++;
                $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
                $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
                foreach($data2 as $line2) {
                    $query .= " UNION ALL select id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ";
                }
                
            }

            $q2 = $db->query($query);
            $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
            foreach($data2 as $line2) {
                $selected = '';
                if($line2['noakun']==$r2['akun_kredit']){$selected = "selected";}
                $rs .= "<option value='".$line2['noakun']."' $selected>".$line2['noakun']." - ".$line2['nama']."</option>";
            }

            $rs .= '</select>';
            if($r2['id']==1){
                $rs .= '<br><b>(*) Bila Akun Dropshipper Kosong</b>';
            }
            $rs .='</td>';
        }
        $rs .= '</tr>';
        $i++;
   }
        echo $rs;