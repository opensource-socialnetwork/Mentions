<script>
$(document).ready(function() {
    var mentionUI = new Tribute({
        menuItemTemplate: function(item) {
            return '<img src="' + item.original.imageurl + '">' + item.string;
        },
        requireLeadingSpace: false,
        values: function(text, cb) {
            mention_users_ui_search(text, users => cb(users));
        },
    });
    mentionUI.attach(document.querySelectorAll("#comment-edit"));
});
</script>
