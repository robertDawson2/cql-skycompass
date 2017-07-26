<table>
<thead>
    <tr>
        <?php foreach($fields as $field) { 
            
            if(strpos($field, '.id')===false && strpos($field, '_id')===false) {
        
?>
        <th>
           <?= ucwords(str_replace(".", "<br /> ", str_replace("_", " ", $field))); ?>
        </th>
        
            <?php } 
        } ?>
    </tr>
</thead>
<tbody>
    <?php foreach($results as $result) {
        echo "<tr>";
        foreach($result as $j => $res) {
            foreach($res as $i => $r)
            {
                if(strpos($i, '_id') === false && ("." . $i) !== ".id") {
                echo "<td>" . $r . "</td>";
            }
            }
        }
        echo "</tr>";
    } ?>
</tbody>

</table>