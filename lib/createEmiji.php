<head>
<!-- Begin emoji-picker Stylesheets -->
<link href="../lib/css/emoji.css" rel="stylesheet">
<!-- End emoji-picker Stylesheets -->

<script src="<?php echo getUrl("/lib/js/jquery.min.js") ?>"></script>

<!-- Begin emoji-picker JavaScript -->
<script src="../lib/js/config.js"></script>
<script src="../lib/js/util.js"></script>
<script src="../lib/js/jquery.emojiarea.js"></script>
<script src="../lib/js/emoji-picker.js"></script>
<!-- End emoji-picker JavaScript -->

<script>
    $(function() {
    window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: '../lib/img/',
        popupButtonClasses: 'btn fa fa-smile-o'
    });

    window.emojiPicker.discover();
    });
</script>
</head>