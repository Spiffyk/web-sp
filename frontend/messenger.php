<?php
$messages = Messenger::getInstance()->getMessages();

foreach ($messages as $message) {
    ?>

    <div class="message <?php echo $message->getClasses(); ?>">
        <div class="message-body">
            <?php echo $message->getMessage(); ?>
        </div>
        <?php
        if (!empty($message->getDetail())) {
            ?>

            <div class="message-detail">
                <?php echo $message->getDetail(); ?>
            </div>

            <?php
        }
        ?>
    </div>

    <?php
}
