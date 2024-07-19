<?php

class DefaultLogin
{
    /** Print login form.
     */
    public function loginForm()
    {
        echo '<p hidden data-plugin="default-login">Plugin on</p>';
        echo "<table cellspacing='0' class='layout'>";
        echo $this->loginFormField(
            'driver',
            '',
            'pgsql'
        );
        echo $this->loginFormField(
            'server',
            'POSTGRES_HOST'
        );
        echo $this->loginFormField(
            'username',
            'POSTGRES_USER'
        );
        echo $this->loginFormField(
            'password',
            'POSTGRES_PASSWORD'
        );
        echo $this->loginFormField(
            'db',
            'POSTGRES_DB'
        );
        echo '</table>';
        echo "<p><input type='submit' value='Login'>";

        return 0;
    }

    public function loginFormField(
        string $name,
        string $envValue = '',
        string $value = ''
    ): string {
        $inputValue = $value;
        if (!empty($envValue) && isset($_ENV[$envValue])) {
            $inputValue = $_ENV[$envValue];
        }

        $nameAttr = "auth[{$name}]";

        return <<<HTML
            <tr>
                <td>
                    {$name}
                </td>
                <td>
                    <input name="{$nameAttr}" value="{$inputValue}" />
                </td>
            </tr>
        HTML;
    }
}

return new DefaultLogin();
