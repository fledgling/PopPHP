Pop PHP Framework
=================

Documentation : Feed
--------------------

Home

提供的功能，读取和解析外部饲料和以有用的方式提供的数据，以及写的功能和显示内容，饲料的饲料成分。

下面是一个例子读数（即解析）外部供给。

    use Pop\Feed;

    $tmpl = <<<NEWS
        <div class="news-div">
            <a href="[{link}]">[{title}]</a><br />
            <strong>[{published}]</strong> ([{time}])<br />
            <p>[{content}]</p>
        </div>

    NEWS;

    $google = 'http://news.google.com/news?pz=1&cf=all&ned=us&hl=en&topic=h&output=rss';
    $feed = Feed\Reader::getByUrl($google, 3);
    $feed->setTemplate($tmpl);
    $feed->render();

下面是一个例子阅读饲料的服务帐户，如Twitter或Facebook上。

    use Pop\Feed;

    $tmpl = <<<POSTS
        <div class="post-div">
            <a href="[{link}]">[{title}]</a><br />
            <strong>[{published}]</strong> ([{time}])<br />
        </div>

    POSTS;

    $twitter = Feed\Reader::getByAccountName('twitter', 'highvoltagenola', 3);
    // OR
    //$twitter = Feed\Reader::getByUrl('http://twitter.com/highvoltagenola', 3);

    $twitter->setTemplate($tmpl);

    echo '<h1>' . $twitter->title . '</h1>' . PHP_EOL;
    echo '<h4>' . $twitter->tweets . ' Tweets : ' .
        $twitter->following . ' Following : ' .
        $twitter->followers . ' Followers</h4>' . PHP_EOL;

    $twitter->render();

下面是一个例子，写一个饲料。

    use Pop\Feed;

    $feedHeaders = array(
        'title'     => 'Test Feed Title',
        'subtitle'  => 'Test Feed Description',
        'link'      => 'http://www.testfeed.com/',
        'language'  => 'en',
        'updated'   => '2010-01-12 13:01:32',
        'generator' => 'http://www.website.com/',
        'author'    => 'Some Editor'
    );

    $entry1 = array(
        'title'    => 'Entry Title 1',
        'link'     => 'http://www.testfeed.com/entry1',
        'comments' => 'http://www.testfeed.com/entry1#comments',
        'author'   => 'Entry Author 1',
        'updated'  => '2010-01-13 14:12:24',
        'summary'  => 'Entry Desc 1'
    );

    $entry2 = array(
        'title'    => 'Entry Title 2',
        'link'     => 'http://www.testfeed.com/entry2',
        'comments' => 'http://www.testfeed.com/entry2#comments',
        'author'   => 'Entry Author 2',
        'updated'  => '2010-01-12 14:12:24',
        'summary'  => 'Entry Desc 2'
    );

    $entry3 = array(
        'title'    => 'Entry Title 3',
        'link'     => 'http://www.testfeed.com/entry3',
        'comments' => 'http://www.testfeed.com/entry3#comments',
        'author'   => 'Entry Author 3',
        'updated'  => '2010-01-11 14:12:24',
        'summary'  => 'Entry Desc 3'
    );

    $feedEntries = array($entry1, $entry2, $entry3);
    $feed = new Feed\Writer($feedHeaders, $feedEntries, Feed\Writer::ATOM);
    $feed->render();

\(c) 2009-2014 [Moc 10 Media, LLC.](http://www.moc10media.com) All
Rights Reserved.
