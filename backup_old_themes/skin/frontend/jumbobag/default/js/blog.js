!function(a){a(document).ready(function(){if(a("body").hasClass("blog-index-list")&&(a(".contenu").each(function(b){var c=a(this).find(".texte").html();""!==c&&a(this).html(c)}),a(".image").click(function(){var b=a(this).find("img").attr("data-href");document.location.href=b})),a("body").hasClass("blog-post-view")){var b="",c=a(".contenu .texte").html(),d=a(".contenu .video").html(),e=a(".contenu .pdf").html(),g=a(".contenu .lien").html();if(""!==c&&(b+="<div class='news_texte'>"+c+"</div>"),b+=""!==g?"<a href='"+g+"' target='_blank'>Lire l'article sur le site</a>":"",""!==e){var h="";h=e.split("=")[1],console.log(h),b+='<hr><div data-configid="'+h+'" style="width:100%; height:371px;" class="issuuembed"></div><script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>'}if(""!==d){var i="";if(d.indexOf("=")>-1)i=d.split("=")[1];else{var j=d.split("/");i=j[j.length-1]}b+='<hr><div class="video_news"><iframe width="100%" height="500" src="http://www.youtube.com/embed/'+i+'"></iframe></div>'}a(".contenu").html(b)}})}(jQuery);