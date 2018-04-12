;(function($) {
	'use strict';
	window.eaelLoadMorePostList = function( settings ) {

		// Settings Values
		var settingsValue = {
			ajaxUrl: settings.ajaxUrl,
			postType: settings.postType,
			perPage: settings.perPage,
			postOrder: settings.postOrder,
			showImage: settings.showImage,
			showTitle: settings.showTitle,
			showExcerpt: settings.showExcerpt,
			excerptLength: settings.excerptLength,
			categories: settings.categories,
			offset: settings.offset,
			totalPosts: settings.totalPosts,
			queryVars: settings.queryVars,
			nextBtn: settings.nextBtn,
			prevBtn: settings.prevBtn,
			postAppender: settings.postAppender,
			postCategoryClass: settings.postCategoryClass,
			listFeatureImage: settings.listFeatureImage,
			postExcerpt: settings.postExcerpt,
			postExcerptLength: settings.postExcerptLength,
			postMeta: settings.postMeta,
			postTitle: settings.postTitle,
			featuredPostMeta: settings.featuredPostMeta,
			featuredPostTitle: settings.featuredPostTitle,
			featuredPostExcerpt: settings.featuredPostExcerpt,
			featuredExcerptLength: settings.featuredExcerptLength
		}
		var postListCat = $(settingsValue.postCategoryClass);
		var newOffset = parseInt(settingsValue.perPage, 10);
		var prevOffset = 0;
		var categoryPosts = settingsValue.totalPosts;

		if( settingsValue.perPage >= settingsValue.totalPosts ) {
			$(settingsValue.nextBtn).prop('disabled', true);
		}

		// On Next Click Request
		$(settingsValue.nextBtn).on('click', function(e) {
			e.preventDefault();
			prevOffset = prevOffset + settingsValue.perPage;
			$(settingsValue.prevBtn).prop('disabled', false);
			var postAvailable = categoryPosts;
			var postCategoryId = $(settingsValue.postCategoryClass+'.active').data('cat-id');
			$.ajax({
				url: settings.ajaxUrl,
				type: 'post',
				data: {
					action: 'load_more_post_list',
					catId: postCategoryId,
					settings: settingsValue,
					newOffset: newOffset,
				},
				success: function(response) {
					newOffset = newOffset + settings.perPage;
					if(newOffset+settings.perPage >= postAvailable) {
						$(settingsValue.nextBtn).prop('disabled', true);
					}
					$(settingsValue.postAppender).html('');
					$(settingsValue.postAppender).append(response);
				},
				error: function(err) {
					console.log(err);
				}
			});
		});

		// On Prev Click Request
		$(settingsValue.prevBtn).prop('disabled', true);
		$(settingsValue.prevBtn).on('click', function(e) {
			e.preventDefault();
			$(settingsValue.nextBtn).prop('disabled', false);
			prevOffset = prevOffset - settingsValue.perPage;
			var postAvailable = categoryPosts - settings.perPage;
			var postCategoryId = $(settingsValue.postCategoryClass+'.active').data('cat-id');
			$.ajax({
				url: settings.ajaxUrl,
				type: 'post',
				data: {
					action: 'load_more_post_list',
					catId: postCategoryId,
					settings: settingsValue,
					newOffset: prevOffset,
				},
				success: function(response) {
					newOffset = newOffset - settings.perPage;
					if(prevOffset <= 0) {
						$(settingsValue.prevBtn).prop('disabled', true);
					}
					$(settingsValue.postAppender).html('');
					$(settingsValue.postAppender).append(response);
				},
				error: function(err) {
					console.log(err);
				}
			});
		});

		// On CLick Ajax Request
		postListCat.on('click', function() {
			postListCat.removeClass('active');
			var self = $(this);
			var catId = self.data('cat-id');
			self.addClass('active');
			// Ajax Request
			$.ajax({
				url: settings.ajaxUrl,
				data: {
					action: 'load_post_list',
					catId: catId,
					settings: settingsValue
				},
				type: 'post',
				success: function(response) {
					newOffset = settingsValue.perPage;
					if( settingsValue.perPage >= categoryPosts ) {
						$(settingsValue.nextBtn).prop('disabled', true);
						$(settingsValue.prevBtn).prop('disabled', true);
					}else {
						$(settingsValue.nextBtn).prop('disabled', false);
					}
					$(settingsValue.postAppender).html('');
					$(settingsValue.postAppender).append(response);
				},
				error: function(err) {
					console.log(err);
				}
			});

			$.ajax({
				url: settings.ajaxUrl,
				type: 'post',
				data: {
					action: 'get_category_post_count',
					catId: catId,
				},
				success: function(response) {
					categoryPosts = response.post_count;
				},
				error: function(err) {
					console.log(err);
				}
			});
		});

	}


})(jQuery);