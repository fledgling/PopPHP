Pop PHP Framework
=================

Documentation : Mail
--------------------

邮件组件提供必要的功能，通过PHP传出邮件管理。这包括支持基于文本和基于HTML的电子邮件，多个邮件收件人，模板和文件附件。

<pre>
use Pop\Mail\Mail;

$rcpts = array(
    array(
        'name'  => 'Test Smith',
        'email' => 'test@email.com'
    ),
    array(
        'name'  => 'Someone Else',
        'email' => 'someone@email.com'
    )
);

$mail = new Mail($rcpts, 'Hello World!');
$mail->setHeaders(array(
    'From'        => array('name' => 'Bob', 'email' => 'bob123@gmail.com'),
    'Reply-To'    => array('name' => 'Bob', 'email' => 'bob123@gmail.com'),
    'X-Mailer'    => 'PHP/' . phpversion(),
    'X-Priority'  => '3',
));

$html = &lt;&lt;&lt;HTMLMSG
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;
        Test HTML Email
    &lt;/title&gt;
    &lt;meta http-equiv="Content-Type" content="text/html; charset=utf-8" /&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;Hello [{name}]&lt;/h1&gt;
    &lt;p&gt;
        I'm just trying out this new Pop Mail Library component.
    &lt;/p&gt;
    &lt;p&gt;
        Thanks,&lt;br /&gt;
        Bob
    &lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;

HTMLMSG;

$mail->setText("Hello [{name}],\n\nI'm just trying out this new Pop Mail component.\n\nThanks,\nBob\n\n");
$mail->setHtml($html);
$mail->attachFile('../assets/files/test.pdf');
$mail->send();
</pre>

(c) 2009-2012 [Moc 10 Media, LLC.](http://www.moc10media.com) All Rights Reserved.