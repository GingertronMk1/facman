<?php

class Autologin
{
    function loginForm()
    {
        $loginTableRows = array_map(
            function ($row) {
                return "<tr><td>{$row}</td></tr>";
            },
            [
                "<input type=\"text\" value=\"{$_ENV['DB_CONNECTION']}\" name=\"auth[driver]\" />",
                "<input type=\"text\" value=\"{$_ENV['DB_HOST']}\" name=\"auth[server]\" />",
                "<input type=\"text\" value=\"{$_ENV['DB_USERNAME']}\" name=\"auth[username]\" />",
                "<input type=\"text\" value=\"{$_ENV['DB_PASSWORD']}\" name=\"auth[password]\" />",
                "<input type=\"text\" value=\"{$_ENV['DB_DATABASE']}\" name=\"auth[db]\" />",
                implode(
                    PHP_EOL,
                    [
                        "<label for=\"auth[permanent]\">",
                        "Permanent?",
                        "<input type=\"checkbox\" name=\"auth[permanent]\" value=\"{$_COOKIE['adminer_permanent']}\" />",
                        "</label>",

                    ]
                ),
                "<input style=\"font-size:144px\" type=\"Submit\" value=\"Login\" />",
            ]
        );
        echo "<table>" . implode(PHP_EOL, $loginTableRows) . "</table>";

        return false;
    }
}

return new Autologin();
