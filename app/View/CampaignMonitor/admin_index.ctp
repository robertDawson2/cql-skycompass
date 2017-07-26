<table id="campaigns" class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>
                Name
            </th>
            <th>
                Sent
            </th>
            <th>
                Recipients
            </th>
            <th>
                Opens
            </th>
            <th>
                Clicks
            </th>
            <th>
                Bounces
            </th>
            <th>
                Unsubscribes
            </th>
            <th>
                Spam
            </th>
        </tr>
    </thead>
</table>

<?php $this->append('jquery-scripts'); ?>

    var campaigntable = $('#campaigns').DataTable({
        ajax: "/admin/campaignMonitor/ajaxGetCampaigns",
        "columns": [
            { "data": "Name" },
            { "data": "SentDate" },
            { "data": "TotalRecipients" },
            { "data": "Opens" },
            { "data": "Clicks" },
            { "data": "Bounces" },
             { "data": "Unsubscribes" },
            { "data": "Spam" }
            
        ]
    });
    //table.ajax.url('/admin/campaignMonitor/ajaxGetCampaigns').load();

<?php $this->end(); ?>

    <?php $this->append('scripts'); ?>
<script>
    $(".fancyframe").fancybox({
        type: 'iframe'
    });
</script>
<?php $this->end(); ?>