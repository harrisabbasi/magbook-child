jQuery(document).ready(function($) {
	const $button = $('#load-more');
	const $nonce = $('#more_posts_nonce');
	const postsPerPage = $button.data("posts");
    const category = $button.data('category');
	const page = $button.data('page');

	$button.on('click', function(event) {
		loadAjaxPosts(event);
	});

	function loadAjaxPosts(event) {
		event.preventDefault();
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
			console.log(response);
			$(".more-like-this").append(response.data);
		})
		.fail(function(error) {

		});
		
	}
});