<?php
header("Content-Type: text/plain");

if (!isset($_GET["confirm"]) || $_GET["confirm"] != "1") {
    die("You need to confirm the installation by adding `?confirm=1` to the address!");
}

require_once __DIR__ . "/core.php";

global $db_prefix;

$db = Database::getInstance();
$pdo = $db->getPdo();

echo "Creating usergroups table...\n";
$pdo->query("CREATE TABLE IF NOT EXISTS `" . $db_prefix . "usergroups` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC)
)");

echo "Creating users table...\n";
$pdo->query("CREATE TABLE IF NOT EXISTS `" . $db_prefix . "users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(45) NOT NULL,
    `passwordhash` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `group` INT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `name_UNIQUE` (`name` ASC),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    UNIQUE INDEX `email_UNIQUE` (`email` ASC),
    INDEX `fk_" . $db_prefix . "users_" . $db_prefix . "usergroups_idx` (`group` ASC),
    CONSTRAINT `fk_" . $db_prefix . "users_" . $db_prefix . "usergroups`
        FOREIGN KEY (`group`)
        REFERENCES `" . $db_prefix . "usergroups` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
)");

echo "Creating permissions table...\n";
$pdo->query("CREATE TABLE IF NOT EXISTS `" . $db_prefix . "permissions` (
    `group` INT NOT NULL,
    `permission` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`group`),
    CONSTRAINT `fk_" . $db_prefix . "permissions_" . $db_prefix . "usergroups1`
        FOREIGN KEY (`group`)
        REFERENCES `" . $db_prefix . "usergroups` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
)");

echo "Creating config table...\n";
$pdo->query("CREATE TABLE IF NOT EXISTS `" . $db_prefix . "config` (
    `key` VARCHAR(255) NOT NULL,
    `value` VARCHAR(255) NULL,
    PRIMARY KEY (`key`)
)");

echo "Creating article table...\n";
$pdo->query("CREATE TABLE IF NOT EXISTS `" . $db_prefix . "article` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `author` INT NOT NULL,
    `created` DATETIME NOT NULL,
    `modified` DATETIME NULL,
    `state` SMALLINT NOT NULL,
    `title` VARCHAR(64) NOT NULL,
    `abstract` LONGTEXT NOT NULL,
    `file` VARCHAR(256) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_" . $db_prefix . "article_" . $db_prefix . "users1_idx` (`author` ASC),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    CONSTRAINT `fk_" . $db_prefix . "article_" . $db_prefix . "users1`
        FOREIGN KEY (`author`)
        REFERENCES `" . $db_prefix . "users` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
)");

echo "Creating review table...\n";
$pdo->query("CREATE TABLE IF NOT EXISTS `" . $db_prefix . "review` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `article` INT NOT NULL,
    `author` INT NOT NULL,
    `proposal` SMALLINT NOT NULL,
    `created` DATETIME NOT NULL,
    `content` LONGTEXT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_" . $db_prefix . "comment_" . $db_prefix . "article1_idx` (`article` ASC),
    INDEX `fk_" . $db_prefix . "comment_" . $db_prefix . "users1_idx` (`author` ASC),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    CONSTRAINT `fk_" . $db_prefix . "comment_" . $db_prefix . "article1`
        FOREIGN KEY (`article`)
        REFERENCES `" . $db_prefix . "article` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_" . $db_prefix . "comment_" . $db_prefix . "users1`
        FOREIGN KEY (`author`)
        REFERENCES `" . $db_prefix . "users` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
)");

echo "Creating user_approval table...\n";
$pdo->query("CREATE TABLE IF NOT EXISTS `" . $db_prefix . "user_approval` (
    `id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `opened` DATETIME NOT NULL,
    `closed` DATETIME NOT NULL,
    `state` SMALLINT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_" . $db_prefix . "user_approval_" . $db_prefix . "users1_idx` (`user_id` ASC),
    CONSTRAINT `fk_" . $db_prefix . "user_approval_" . $db_prefix . "users1`
        FOREIGN KEY (`user_id`)
        REFERENCES `" . $db_prefix . "users` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
)");

echo "Creating `anonymous` group...\n";
$anon_group = new UserGroup();
$anon_group->setId(UserGroup::GROUP_ANONYMOUS);
$anon_group->setName("anonymous");
$anon_group->dao_create();

echo "Creating `unverified` group...\n";
$unver_group = new UserGroup();
$unver_group->setId(UserGroup::GROUP_UNVERIFIED);
$unver_group->setName("unverified");
$unver_group->dao_create();

echo "Creating `root` group...\n";
$root_group = new UserGroup();
$root_group->setId(UserGroup::GROUP_ROOT);
$root_group->setName("root");
$root_group->addPermission("root");
$root_group->dao_create();

echo "Creating `root` user...\n";
$root_user = new User();
$root_user->setName("root");
$root_user->setEmail("root@example.com");
$root_user->setGroup($root_group);
$root_user->setPasswordhash(password_hash("toor", PASSWORD_BCRYPT));
$root_user->dao_create();

echo "Setting default configuration...\n";
$config = Config::getInstance();
$config->set("pagetitle", "Conference");

echo "--- All done. ---\n";
