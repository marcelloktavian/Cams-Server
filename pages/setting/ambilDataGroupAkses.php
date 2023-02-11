<?php

include("../../include/config.php");
$group_id = $_POST['group_id'];
 $query = $db->query("select m.menu_group,m.menu_id,m.menu_name, m.policy, m.url, g.policy as group_policy, m.menu_parent "
            . "from menu m left join group_access g on g.menu_id = m.menu_id  and g.group_id = '$group_id'   "
            . "where (hide=0 or hide is null)  order by m.menu_id ");
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    $rs = '';
   $i=1; $no=1;
    foreach($rows as $r) {
        $menugroup ='';
        $hidden='';
        if ($r['url']=='#'){
//            $menugroup = 'colspan="5" style="background-color:#3438;color:#fff";font-style:bold';
            $menugroup = 'style="background-color:#3438;color:#fff";font-style:bold';
        }
        if ($i==1){
            $hidden = '	<input type="hidden" name="rowcount" value="'.count($rows).'">';
            //<input type="hidden" name="group_id" value="'.$group_id.'">	
        }
        $rs .= '<tr>';
        $rs .= '<td '.$menugroup.'>'.$hidden.$r['menu_name'].'<input type="hidden" name="menu_id[]" value="'.$r['menu_id'].'"></td>';
       if ($menugroup==""){
           $rs .= '<td align="center">'.(strpos($r['policy'],'VIEW;')=== false?'':
                   '<input type="checkbox" class="checkBoxView" id="chkView'.$r['menu_group'].$i.'" name="chkView'.$i.'" '.(strpos($r['group_policy'],'VIEW;')=== false?'':'checked="checked"').'>').'&nbsp;</td>	';
           $rs .= '<td align="center">'.(strpos($r['policy'],'ADD;')=== false?'':
                   '<input type="checkbox" class="checkBoxAdd" id="chkAdd'.$r['menu_group'].$i.'" name="chkAdd'.$i.'" '.(strpos($r['group_policy'],'ADD;')=== false?'':'checked="checked"').'>').'&nbsp;</td>	';
           $rs .= '<td align="center">'.(strpos($r['policy'],'EDIT;')=== false?'':
                   '<input type="checkbox" class="checkBoxEdit" id="chkEdit'.$r['menu_group'].$i.'" name="chkEdit'.$i.'" '.(strpos($r['group_policy'],'EDIT;')=== false?'':'checked="checked"').'>').'&nbsp;</td>	';
           $rs .= '<td align="center">'.(strpos($r['policy'],'DELETE;')=== false?'':
                   '<input type="checkbox" class="checkBoxDelete" id="chkDelete'.$r['menu_group'].$i.'" name="chkDelete'.$i.'" '.(strpos($r['group_policy'],'DELETE;')=== false?'':'checked="checked"').'>').'&nbsp;</td>	';
           $rs .= '<td align="center">'.(strpos($r['policy'],'POST;')=== false?'':
                   '<input type="checkbox" class="checkBoxPost" id="chkPost'.$r['menu_group'].$i.'" name="chkPost'.$i.'" '.(strpos($r['group_policy'],'POST;')=== false?'':'checked="checked"').'>').'&nbsp;</td> ';
//           $rs .= '<td align="center"><input type="checkbox" name="chkAdd[]"></td>';
//            $rs .= '<td align="center"><input type="checkbox" name="chkEdit[]"></td>';
//            $rs .= '<td align="center"><input type="checkbox" name="chkDelete[]"></td>';                                        
       }else{
            $rs .= '<td align="center">'.(strpos($r['policy'],'VIEW;')=== false?'':
                   '<input type="checkbox" class="checkBoxView" id="chkView'.$r['menu_group'].$i.'" name="chkView'.$i.'" '.(strpos($r['group_policy'],'VIEW;')=== false?'':'checked="checked"').'>').'&nbsp;</td>	';
       }
           

        $rs .= '</tr>';
        $i++;
    }
        echo $rs;