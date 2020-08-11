
</div>
</div>
</div>
<!-- /container -->

<!-- jQuery -->
<script src="<?= URL ?>public/js/jquery.min.js"></script>
<script src="<?= URL ?>public/js/bootstrap.min.js"></script>
<script src="<?= URL ?>public/js/metisMenu.min.js"></script>
<?php
if (isset($this->js)) {
    foreach ($this->js as $js) {
        ?><script src="<?= URL ?>public/<?= $js ?>"></script><?php
    }
}
?>
<script src="<?= URL ?>public/js/default.js"></script>

</body>

</html>