
<style>
    .extra-info {
        position: absolute;
        bottom: 0;
        left: 0;
        padding: 5px;
        width: 100%;
        text-align: center;
        font-style: italic;
        background: rgba(0,0,0,0.2);
    }
    
    </style>
     <div class="row">
        <div class="col-md-12">
            <div class="body pseudo-text">
                <?= $active['EmailTemplate']['content']; ?>
            </div>
        </div>
       
    </div>
<div class="extra-info">
    From: <?= $active['EmailTemplate']['email_from']; ?> &nbsp;
    Reply To: <?= $active['EmailTemplate']['reply_to']; ?>
</div>