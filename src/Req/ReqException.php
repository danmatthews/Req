<?php

namespace Req;

class ReqException extends Exception
{
    public function __construct($error = "A general, unknown error occured")
    {
        if (is_array($error)) {
            $error = '<li>'.implode("</li><li>", $error).'</li>';
        } else {
            $error = '<li>'.$error.'</li>';
        }

        $output = array(
            '<div style="background:#FCF8E5;font-family:sans-serif;',
            'padding:10px;border:1px solid #F2E498;" class="req-exception">',
            '<h2>A request error occured</h2>',
            '<ul>',
            $error,
            '</ul>',
            '</div>',
        );

        echo implode("", $output);

    }
}
