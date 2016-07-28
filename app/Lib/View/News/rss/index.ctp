<?php
$this->set('channelData', array(
    'title' => __("The Council on Quality and Leadership"),
    'link' => $this->Html->url('/', true),
    'description' => __("Most Recent News"),
    'language' => 'en-us'
));

foreach ($news as $n) {
    $postTime = strtotime($n['News']['created']);

    $postLink = array(
        'controller' => 'news',
        'action' => 'view',
        $n['News']['id']
    );

    // Remove & escape any HTML to make sure the feed content will validate.
    $bodyText = h(strip_tags($n['News']['content']));
    $bodyText = $this->Text->truncate($bodyText, 400, array(
        'ending' => '...',
        'exact'  => true,
        'html'   => true,
    ));

    echo  $this->Rss->item(array(), array(
        'title' => $n['News']['headline'],
        'link' => $postLink,
        'guid' => array('url' => $n['News']['url'], 'isPermaLink' => 'true'),
        'description' => $bodyText,
        'pubDate' => $n['News']['created']
    ));
}

?>