<?php
ob_start();
?>
    <div style="margin-top: 30px; font-size: 13px; color: #777; text-align: center;">
        <p>You're receiving this email because you're a registered user of Swift Contract.</p>
        <p>&copy; <?= date('Y') ?> Swift Contract. All rights reserved.</p>
    </div>
</div>
</body>
</html>
<?php
$emailFooter = ob_get_clean();
