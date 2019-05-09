(function($) {
    $(function() {
        var instaregex = /https?:\/\/(?:www.)?instagram\.com\/p\/([A-z0-9_\-]+)(?:[^\s])+/
        $(".instagram-posts").each(function() {
            this.innerHTML = this.innerHTML.match(new RegExp(instaregex, 'g')).map(function(url, index) {
                var id = (url.match(instaregex) || [false, false])[1];
                return id ? "<a href='https://www.instagram.com/p/" + id + "' class='post wow fadeIn"+(index % 2 === 0 ? "Left" : "Right")+"'><img src='https://www.instagram.com/p/" + id + "/media/?size=m'></a>" : null;
            }).filter(Boolean).join("");
            this.classList.add("show")
        });
    });
})(jQuery);
