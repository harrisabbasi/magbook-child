jQuery(document).ready(function($) {
	const $nonce = $('#more_posts_nonce');
	
	$(document).on("click", "#load-more", function() {
	    loadAjaxPosts(event);;
	 });

	function loadAjaxPosts(event) {
		event.preventDefault();
		var $button = $('#load-more');
		var postsPerPage = $button.data("posts");
	    var category = $button.data('category');
		var page = $button.data('page');

		$.ajax({
			'type': 'POST',
			'url': magbookAjaxLocalization.ajaxurl,
			'data': {
				'postsPerPage': postsPerPage,
				'paged':page,
				'category': category,
				'morePostsNonce': $nonce.val(),
				'action': magbookAjaxLocalization.action,
			}
		})
		.done(function(response) {
			$button.remove();
			$(".more-like-this").append(response.data);
		})
		.fail(function(error) {

		});
		
	}
});