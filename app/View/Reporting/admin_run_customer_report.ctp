<table>
<thead>
    <tr>
        <th></th>
        <?php foreach($fields as $field) { 
            
           
?>
        <th>
           <?= ucwords(str_replace(".", "<br /> ", str_replace("_", " ", $field))); ?>
        </th>
        
            <?php  
        } ?>
    </tr>
</thead>
<tbody>
    <?php foreach($results as $i => $result) {
        echo "<tr>";
        echo "<td data-id='" . $i . "'><input type='checkbox' class='result-checkbox' /></td>";
        foreach($result as $j => $res) {
            foreach($res as $i => $r)
            {
                
                echo "<td>" . $r . "</td>";
            
            }
        }
        echo "</tr>";
    } ?>
</tbody>

</table>