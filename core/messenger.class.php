<?php
class Messenger {

    public const TYPE_INFO = 0;
    public const TYPE_SUCCESS = 1;
    public const TYPE_ERROR = 2;

    private const INFO_CLASSES = "info-msg";
    private const SUCCESS_CLASSES = "success-msg";
    private const ERROR_CLASSES = "error-msg";

    private static $instance;

    private $messages;

    /**
     * Gets the singleton instance of Messenger
     *
     * @return Messenger the singleton instance of Messenger
     */
    public static function getInstance(): Messenger {
        if (empty($instance)) {
            $instance = new Messenger();
        }

        return $instance;
    }

    private function __construct() {
        $this->messages = array();
    }

    public function getMessages(): array {
        return $this->messages;
    }

    /**
     * Sends a new message to the messenger.
     *
     * @param int $type the type of the message (<code>TYPE_</code> constants of the messenger)
     * @param string $message the body of the message
     * @param null|string $detail the (optional) detail
     */
    public function message(int $type, string $message, ?string $detail=null) {
        $message_obj = new Message();

        switch ($type) {
            case self::TYPE_SUCCESS:
                $message_obj->setClasses(self::SUCCESS_CLASSES);
                break;
            case self::TYPE_ERROR:
                $message_obj->setClasses(self::ERROR_CLASSES);
                break;
            case self::TYPE_INFO:
            default:
                $message_obj->setClasses(self::INFO_CLASSES);
        }

        $message_obj->setMessage($message);
        $message_obj->setDetail($detail);

        array_push($this->messages, $message_obj);
    }
}
