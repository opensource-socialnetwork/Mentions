//<script>
function mention_users_ui_search(text, cb) {
    var URL = Ossn.site_url + "mentions_picker";
    xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                cb(data);
            } else if (xhr.status === 403) {
                cb([]);
            }
        }
    };
    xhr.open("GET", URL + "?q=" + text, true);
    xhr.send();
}
//bug in chrome that re-creates old element after removing using backspace
//https://stackoverflow.com/questions/31207738/contenteditable-re-creates-deleted-child-elements
$(document).ready(function() {
    if ($('.comment-box').length > 0) {
        var mentionUI = new Tribute({
            menuItemTemplate: function(item) {
                return '<img src="' + item.original.imageurl + '">' + item.string;
            },
            selectTemplate: function(item) {
                return '<p contenteditable="false" class="tribute-mention">@' + item.original.value + '</p> ';
            },
            requireLeadingSpace: false,
            values: function(text, cb) {
                mention_users_ui_search(text, users => cb(users));
            },
            noMatchTemplate: function (tribute) {
                return '<li>'+Ossn.Print('mentionsui:nomatch')+'</li>';
            },	
        });
        mentionUI.attach(document.querySelectorAll(".comment-box"));
        document.querySelector('[contenteditable=true]').addEventListener('DOMNodeInserted', function(event) {
            if (event.target.tagName == 'SPAN') {
                event.target.outerHTML = event.target.innerHTML;
                event.target.remove();
            }
        });
    }
});
$(document).ajaxComplete(function(event, xhr, settings) {
    var substrings = ['?offset='];
    if (substrings.some(substrings => settings.url.includes(substrings))) {
        var mentionUI = new Tribute({
            menuItemTemplate: function(item) {
                return '<img src="' + item.original.imageurl + '">' + item.string;
            },
            selectTemplate: function(item) {
                return '<p contenteditable="false" class="tribute-mention">@' + item.original.value + '</p> ';
            },
            requireLeadingSpace: false,
            values: function(text, cb) {
                mention_users_ui_search(text, users => cb(users));
            },
        });		
        mentionUI.attach(document.querySelectorAll(".comment-box"));
    }
});