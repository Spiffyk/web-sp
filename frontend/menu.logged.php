<ul>
    <li class="username-display"><?php echo Session::getInstance()->getUser()->getName(); ?></li>
    <li class="userarticles-link"><a href="?action=userarticles">My articles</a></li>
    <li class="logout-link"><a href="?action=logout">Logout</a></li>
</ul>
