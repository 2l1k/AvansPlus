<?php print '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<response>
    <?php
    if(!empty($osmp_txn_id)){
        echo "<osmp_txn_id>{$osmp_txn_id}</osmp_txn_id>";
    }
    if(!empty($prv_txn)){
        echo "<prv_txn>{$prv_txn}</prv_txn>";
    }
    if(!empty($sum)){
        echo "<sum>{$sum}</sum>";
    }
    if(isset($result)){
        echo "<result>{$result}</result>";
    }
    if(!empty($comment)){
        echo "<comment>{$comment}</comment>";
    }
    if(!empty($fields)){
        echo "<fields>";
        $i=0;
        foreach($fields as $field_key => $field_val){
            echo '<field'. ++$i .' name="'. $field_key .'">'. ceil($field_val) .'</field'. $i .'>';
        }
        echo "</fields>";
    }
    ?>
</response>