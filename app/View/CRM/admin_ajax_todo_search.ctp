<style>
    #searchResults, #custSearchResults {
        border: 1px inset #444;
        box-shadow: 4px 4px 2px #555;
         left: 90%; border-radius: 10px; width: 390px; overflow-x: hidden;
    }
    #custSearchResults {
        left: 10%;
    }
    div.search-results {
        width: 400px;
        height: 425px;
        overflow-y: scroll;
        overflow-x: hidden;
        background: rgba(0,0,0, 0.93);
    }
    #custSearchResults > div.search-results {
        height: 350px;
    }
    div.search-results hr {
        margin: 5px 10px;
    }
    .search-header {
        padding: 5px;
        padding-left: 10px;
        font-style: italic;
        color: #ADD7F0;
       // border-bottom: 1px solid #dfdfdf;
    }
    a.search-result {
        width: 100%;
        display: block;
        padding: 5px 10px;
        text-decoration: none;
        color: #eee;
        background-color: rgba(50,50,50,0.4);
        border-bottom: 2px solid #5a626b;
        margin: 0;
        font-size: 10px;
    }
    a.search-result:hover {
        color: #efefef;
        background-color: rgba(50,50,50,0.7);
        
    }
    a.search-result:nth-child(2n) {
        color: #efefef;
        background-color: rgba(0,0,0,0.4);
        border-bottom: 2px solid #384049;
    }
    a.search-result:nth-child(2n):hover {
        color: #ddd;
        background-color: rgba(0,0,0,0.7);
    }
    
</style>

<div class="search-results">
    <div class='box-tools pull-right'><a style='margin:10px;' onclick='hideQSearch();' role='button'><i class='fa fa-close'></i></a></div>
<?php if(isset($data['contacts']) && !empty($data['contacts'])): ?>
<h4 class="search-header">Contacts</h4>
<p>
<?php foreach($data['contacts'] as $info): ?>
<a role='button' class="search-result" onclick='defineLink(this);' data-context='contact' data-id='<?= $info['Contact']['id']; ?>'>
    <i class="fa fa-book"></i> <?= $info['Contact']['first_name'] . " " . $info['Contact']['last_name']; ?></a>
<?php endforeach; ?>
</p>
<hr>
<?php endif; ?>

<?php if(isset($data['customers']) && !empty($data['customers'])): ?>
<h4 class="search-header">Customers</h4>
<p>
<?php foreach($data['customers'] as $info): ?>
<a role='button' class="search-result" onclick='defineLink(this);' data-context='customer' data-id='<?= $info['Customer']['id']; ?>'>
 <i class="fa fa-building"></i> <?= $info['Customer']['name']; ?></a>
<?php endforeach; ?>
</p>
<hr>
<?php endif; ?>


</div>
