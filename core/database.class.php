<?php
/**
 * Database singleton class.
 */
class Database {

    public const DATE_FORMAT = "Y-m-d H:i:s";

    private static $instance = null;

    public $wasCached = false;
    private $pdo;
    private $prefix;

    /**
     * Database singleton constructor.
     */
    private function __construct() {
        require __DIR__ . "/../appconfig.php";
        global $db_host, $db_schema, $db_user, $db_password, $db_prefix;

        $this->pdo = new PDO(sprintf("mysql:host=%s;dbname=%s", $db_host, $db_schema), $db_user, $db_password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->prefix = $db_prefix;
    }

    /**
     * Gets the singleton instance of {@link Database}.
     *
     * @return Database the instance
     */
    public static function getInstance(): Database {
        if (Database::$instance == null) {
            Database::$instance = new Database();
        }

        return Database::$instance;
    }

    /**
     * Gets the {@link PDO} instance used by {@link Database}.
     *
     * @return PDO the PDO instance
     */
    public function getPdo(): PDO {
        return $this->pdo;
    }

    /**
     * Gets the prefix string used by {@link Database}.
     *
     * @return string the prefix
     */
    public function getPrefix(): string {
        return $this->prefix;
    }

    /**
     * Gets a table name for {@link Database}, including its prefix.
     *
     * @return string the table name
     */
    public function table($name): string {
        return $this->prefix.$name;
    }
}