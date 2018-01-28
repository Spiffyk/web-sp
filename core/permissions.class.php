<?php
/**
 * A utility class containing all permission codes.
 */
class Permissions {

    /**
     * A full-control permission. Overrides all permissions and grants full control.
     */
    public const ROOT = "root";

    /**
     * A permission to log in.
     */
    public const LOGIN = "login";

    /**
     * A permission to accept or reject registered users.
     */
    public const USER_APPROVAL = "user_approval";

    /**
     * A permission to read articles.
     */
    public const ARTICLE_READ = "article_read";

    /**
     * A permission to review articles.
     */
    public const ARTICLE_REVIEW = "article_review";

    /**
     * A permission to create articles.
     */
    public const ARTICLE_CREATE = "article_creation";

    /**
     * A permission to accept or reject articles.
     */
    public const ARTICLE_APPROVAL = "article_approval";


    private function __construct(){
        // hidden constructor
    }
}