<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Infrastructure;

class FlashMessage
{
    /**
     * Flash messages to be displayed in the next request.
     */
    private static array $next = [];

    /**
     * Previously-setted Flash messages to be displayed.
     */
    private static array $previous = [];

    /**
     * Class constructor.
     *
     * If any flash message is set, stores the values in a variable
     * and unset the Session flash messages for further use.
     */
    public function __construct()
    {
        if (isset($_SESSION[FLASH_MESSAGE])) {
            self::$previous = $_SESSION[FLASH_MESSAGE];
            unset($_SESSION[FLASH_MESSAGE]);
        }
    }

    /**
     * Class desctructor.
     *
     * Set the Session flash messages for further use.
     */
    public function __destruct()
    {
        $_SESSION[FLASH_MESSAGE] = self::$next;
    }

    /**
     * Add/store flash messages.
     *
     * @param array $messages Array containing the messages in the form "key" => "message".
     *
     * @return void
     */
    public function add(array $messages): void
    {
        foreach ($messages as $name => $message) {
            self::$next[$name][] = $message;
        }
    }

    /**
     * Return flash message array by its "key".
     *
     * OBS: To get a flash message as string, use FlashMessage::getImploded().
     *
     * @param string $name Flash message "key" name.
     *
     * @return ?array If flash message 'name' is not found, return NULL.
     */
    public function getArray(string $name): ?array
    {
        return self::$previous[$name] ?? null;
    }

    /**
     * Return imploded flash message by its "key".
     *
     * If the flash message has multiple messages, implode then using the $separator parameter as glue.
     *
     * @param string $name      Flash message "key" name.
     * @param string $separator Messages string separator. Defaults to a dot followed by a space.
     *
     * @return ?string If flash message 'name' is not found, return NULL.
     */
    public function get(string $name, string $separator = '. '): ?string
    {
        if (!isset(self::$previous[$name])) {
            return null;
        }

        return is_string(self::$previous[$name]) ? self::$previous[$name] : implode($separator, self::$previous[$name]);
    }
}
