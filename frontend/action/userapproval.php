<h2>Uživatelé ke schválení</h2>

<?php
const USER_APPROVALS_PER_PAGE = 10;

if (Session::getInstance()->getGroup()->hasPermission("user_approval")) {
    if (empty($_GET["page"])) {
        $current_page = 0;
    } else {
        $current_page = $_GET["page"];
    }

    $no_of_approvals = UserApproval::dao_countOpen();

    if ($no_of_approvals == 0) {
        ?>

        Žádné žádosti o schválení.

        <?php
    } else {
        $no_of_pages = ceil($no_of_approvals / USER_APPROVALS_PER_PAGE);

        $approvals = UserApproval::dao_getOpen(USER_APPROVALS_PER_PAGE, ($current_page) * USER_APPROVALS_PER_PAGE);

        ?>

        <table>
            <colgroup>
                <col style="width: 35%;">
                <col>
                <col style="width: 1%;">
                <col style="width: 1%;">
            </colgroup>
            <thead>
            <tr>
                <td>Uživatelské jméno</td>
                <td>E-mail</td>
                <td>Schválení</td>
                <td>Zamítnutí</td>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($approvals as $approval) {
                $user = $approval->getUser();
                ?>

                <tr>
                    <td><?php echo $user->getName(); ?></td>
                    <td><?php echo $user->getEmail(); ?></td>
                    <td>
                        <form style="white-space: nowrap;" method="POST">
                            <input type="hidden" name="cmd" value="user-accept">
                            <input type="hidden" name="approval" value="<?php echo $approval->getId(); ?>">
                            <select name="role" title="Role přidělená po schválení">
                                <option value="reader">Čtenář</option>
                                <option value="reviewer">Recenzent</option>
                                <option value="author">Autor</option>
                                <option value="admin">Admin</option>
                            </select>
                            <input type="submit" value="Schválit">
                        </form>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="cmd" value="user-reject">
                            <input type="hidden" name="approval" value="<?php echo $approval->getId(); ?>">
                            <input type="submit" value="Zamítnout">
                        </form>
                    </td>
                </tr>

                <?php
            }
            ?>
            </tbody>
        </table>

        <div class="pager">

            <?php
            for ($i = 0; $i < $no_of_pages; $i++) {
                if ($i == $current_page) {
                    echo "<strong>" . ($i + 1) . "</strong> ";
                } else {
                    echo "<a href=\"?action=user-approval&page=" . $i . "\">" . ($i + 1) . "</a> ";
                }
            }
            ?>

        </div>

        <?php
    }
} else {
    ?> Nemáte oprávnění ke schvalování uživatelů. <?php
}



