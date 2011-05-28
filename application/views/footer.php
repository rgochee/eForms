    </div>
</div>

<div id="footer">
    <div id="footer_inner">
        <div class="left">&copy; CS 130 eForms Team</div>
        <div class="right">
        <?php if($this->session->userdata('admin')): ?>
        <?php echo anchor("admin/changeMode/user?next=" . uri_string(), "User view"); ?>
        <?php else: ?>
        <?php echo anchor("admin/changeMode/admin?next=" . uri_string(), "Admin view"); ?>
        <?php endif ?>
        | About Us | Feedback</div>
        <div class="clear"></div>
    </div>
</div>

</body>
</html>
