(function ($){
	$(document).ready(function() {

		if ($("body").hasClass("blog-index-list")){
			$( ".contenu" ).each(function( index ) {
			  var texte = $(this).find(".texte").html();
			  if (texte !== "") {
			  	$(this).html(texte);
			  };


			});
			$(".image").click(function(){
				var lien_img = $(this).find('img').attr('data-href');
				document.location.href = lien_img;

			}
		)};



		if ($("body").hasClass("blog-post-view")){


		    // var x = document.getElementsByClassName("blog");
		    // x.innerHTML = '<a href="http://www.jumbobag.fr/on-parle-de-nous/" title="Return to ">On parle de nous</a>';


			var result = "";

			var texte = $(".contenu .texte").html();
			var video = $(".contenu .video").html();
			var pdf = $(".contenu .pdf").html();
			var file = '/media/pdfs/' + pdf;
			var lien = $(".contenu .lien").html();


			if (texte !== "") {
				result += "<div class='news_texte'>" + texte + "</div>";
			}

			if (lien !== "") {
				result += "<a href='" + lien + "' target='_blank'>Lire l'article sur le site</a>";
			}else{
				result += "";
			}

			if (pdf !== "") {
				var pdf_id = ""
				pdf_id = pdf.split("=")[1]
				console.log(pdf_id);

				result += '<hr><div data-configid="' + pdf_id + '" style="width:100%; height:371px;" class="issuuembed"></div><script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>';
			}

			if (video !== "") {
				var  youtube_id = "";

				if (video.indexOf("=") > -1) {
					youtube_id = video.split("=")[1];
				}else{
					var tmp = video.split("/");
					youtube_id = tmp[tmp.length - 1 ];
				}
				result += '<hr><div class="video_news"><iframe width="100%" height="500" src="http://www.youtube.com/embed/' + youtube_id + '"></iframe></div>';
			}


			$(".contenu").html(result);

			/*if ($(".open_pdf").length) {
				$( ".open_pdf" ).click(function(event) {
					event.preventDefault();
					$.modal('<iframe src="' + file + '" width="100%" height="700"></iframe>');

				});
			};*/
		}
	});

})(jQuery);


/* <?php echo $post['address'] ?> */
