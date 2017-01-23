

<!-- <div class="footer_wrapper white_back">
    <footer id="globalfooter" class="footer_global container">
        <ul class="footer_links pull-right">
            <li><a href="<?php echo site_url('tips') ?>">Tips</a></li>
            <li><a href="<?php echo site_url('terms') ?>">Terms of Use</a></li>
            <li><a href="<?php echo site_url('policy') ?>">Privacy Policy</a></li>
            <li><a href="<?php echo site_url('about') ?>" class="last">About Us</a></li>
        </ul>
        <p class="footer_copyright col-md-5 clear">Copyright &copy; 2016 Relayy, Inc</p>
    </footer>
</div> -->

<script src="<?= asset_base_url()?>/libs/jquery.min.js" type="text/javascript"></script>
<script src="<?= asset_base_url()?>/libs/bootstrap.min.js" type="text/javascript"></script>

<script src="<?= asset_base_url()?>/libs/quickblox.min.js"></script>
<script src="<?= asset_base_url()?>/js/config.js"></script>
<script src="<?= asset_base_url()?>/js/page_home.js"></script>

<script>
    function startChat() {
      window.location.href = "<?= site_url('chat')?>";
    }
</script>

<?php  if (isset($js_home) && $js_home == 2) { ?>
<script>
  $("#loginForm").modal("show")

</script>
<?php } ?>
 
    </body>
</html>