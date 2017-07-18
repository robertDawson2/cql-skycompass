
<?php $this->append('scripts'); ?>
<script>
    $(".iframe").fancybox();
    
    </script>

<?php $this->end(); ?>


            <h3>Internal Communications</h3>
            <table id='communicationsTable' class="table table-responsive table-striped table-hover table-condensed export-dataTable">
                <thead>
                    <tr>
                        <th class='show-on-export'>
                            Date
                        </th>
                        <th class='show-on-export'>
                            Context
                        </th>
                        <th class='show-on-export'>
                            Recipient Name
                        </th>
                        <th class='show-on-export'>
                            Recipient Email
                        </th>
                        <th class='show-on-export'>
                            Subject
                        </th>
                        <th class='show-on-export'>
                            Email Template
                        </th>
                        <th class='show-on-export'>
                            Result
                        </th>
                    </tr>
                    
                </thead>
                <tbody>
                    <?php foreach($internalCommunications as $comm): ?>
                    <tr>
                        <td>
                            <?= date('m/d/Y H:i', strtotime($comm['Communication']['created'])); ?>
                        </td>
                        <td>
                            <?= ucwords($comm['Communication']['context']); ?>
                        </td>
                        <td>
                            <?= $comm['Contact']['first_name'] . " " . $comm['Contact']['last_name']; ?>
                        </td>
                        <td>
                            <?= $comm['Contact']['email']; ?>
                        </td>
                        <td>
                            <?= $comm['Communication']['template_subject']; ?>
                        </td>
                        <td>
                            <a href='/admin/emailTemplates/preview/<?= $comm['Communication']['email_template_id']; ?>' class='iframe'>
                            <?= $comm['Communication']['template_name']; ?></a>
                        </td>
                        <td>
                            <?= ucwords($comm['Communication']['result']); ?>
                        </td>
                    </tr>
                    
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <hr>
            
                        <h3>External Communications</h3>
            <table id='externalCommTable' class="table table-responsive table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>
                            Id
                        </th>
                        <th class='show-on-export'>
                            Type
                        </th>
                        <th class='show-on-export'>
                            Name
                        </th>
                        <th class='show-on-export'>
                            Open Count
                        </th>
                        <th class='show-on-export'>
                            Link Clicks
                        </th>
                        
                    </tr>
                    
                </thead>
                <tbody>
                    
                </tbody>
            </table>
           
            
            <?php $this->append('jquery-scripts'); ?>
                        var buttonCommon = {
        exportOptions: {
           columns: ['.show-on-export' ],
            format: {
                body: function ( data, row, column, node ) {
                    
                    return data.replace( /<br\s*\/?>/ig, "\n" );
                }
            }
        }
    };
                var externalTable = $("#externalCommTable").DataTable({
             // destroy: true,
               ajax: {
               url: '/admin/campaignMonitor/ajaxGetExternalById/<?=  $contact['Contact']['id']; ?>',
             //  dataSrc: ""
               },
               columns: [
               {data: "id"},
                {data: "type"},
                {data: "name"},
                {data: "open-count"},
                {data: "link-click"}
               ],
               order: [[0, "asc"]],
               dom: 'Bfrtip',
        buttons: [
            $.extend( true, {}, buttonCommon, {
                extend: 'csvHtml5',
                title: fileName
            }),
            $.extend( true, {}, buttonCommon, {
                extend: 'excelHtml5',
                title: fileName
            }),
            $.extend( true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                title: fileName
            }),
            'copy',
            $.extend( true, {}, buttonCommon, {
                extend: 'print'
            })
        ]
           });
            <?php $this->end(); ?>
      
