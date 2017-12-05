<?php
/**
 * Auto-saving configuration singleton class.
 */
class Config {

    private static $instance = null;

    private $config;
    private $hasChanged = false;

    /**
     * Config singleton constructor.
     */
    private function __construct() {
        $db = Database::getInstance();
        $response =
            $db
                ->getPdo()
                ->query("SELECT * FROM `" . $db->table("config") . "`")
                ->fetchAll(PDO::FETCH_ASSOC);

        $this->config = array();
        foreach($response as $item) {
            $this->config[$item["key"]] = $item["value"];
        }
    }

    /**
     * Saves the configuration, if changed.
     */
    function __destruct() {
        if ($this->hasChanged) {
            $db = Database::getInstance();
            $statement =
                $db
                    ->getPdo()
                    ->prepare("INSERT INTO `" . $db->table("config") . "` (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value`=:value");

            $statement->bindParam("key", $key);
            $statement->bindParam("value", $value);

            foreach ($this->config as $key => $value) {
                $statement->execute();
            }
        }
    }

    /**
     * Gets the singleton instance of {@link Config}.
     *
     * @return Config the instance
     */
    public static function getInstance(): Config {
        if (Config::$instance == null) {
            Config::$instance = new Config();
        }

        return Config::$instance;
    }

    /**
     * Gets a config value.
     *
     * @param string $key the key
     * @return string the value
     */
    public function get(string $key): string {
        return $this->config[$key];
    }

    /**
     * Sets a config value.
     *
     * @param string $key the key
     * @param string $value the value
     */
    public function set(string $key, string $value): void {
        $this->config[$key] = $value;
        $this->hasChanged = true;
    }

    /**
     * Prints out the config map.
     */
    public function printOut(): void {
        echo "Config: {\n";
        foreach ($this->config as $key => $value) {
            echo "  " . $key . " => \"" . $value . "\";\n";
        }
        echo "}";
    }

}