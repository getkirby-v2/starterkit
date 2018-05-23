(function($) {

	$.fn.auxiliary = function() {
		// repeat for all input items
		return this.each(function() {

			var $this = $(this);

			// ?? some verification ??
			if($this.data('auxiliary')) {
				return;
			} else {
				$this.data('auxiliary', true);
			}

			// some styling
			var $icon = $this.next('.field-icon');
			$icon.css({
				'cursor': 'pointer',
				'pointer-events': 'auto'
			});

			$icon.on('click', function() {
				$("body").css({'cursor':'wait'});
				$icon.css({
					'cursor': 'wait',
					'pointer-events': 'none'
				});

				// disable future click events
					// gray it out
					// add 'wait' cursor

				var url = $.trim($this.val()); // Removes the whitespace from the beginning and end of a string

				if(url !== '' && $this.is(':valid')) { // validation not working somehow
					metascraper(url);
					// console.log(serviceUrl)
					// window.open(serviceUrl);
				} else {
					console.log("invalid")
					$this.focus();
				}

			});


			function metascraper(url){

				var serviceUrl = "https://micro-open-graph-mvnhoamdcv.now.sh?url=" + url;
				$.ajax({
						url: serviceUrl,
						// timeout: 3000, //3 second timeout
						timeout: 30000, //30 second timeout
						dataType: 'json'
					}).done(function(data){
						console.log(data);
						$.each( data, function( key, val ) {
							if ( key == "message" ) {
								// info retrieved, but it is an error…
								alert(val);
								return;
							} else if ( key == "title" ) {
								$("#form-field-title").val( val );
							} else if ( key == "description" ) {
								$("#form-field-description").val( val );
							} else if ( key == "author" ) {
								$("#form-field-author").val( val );
							} else if ( key == "publisher" ) {
								$("#form-field-publisher").val( val );
							} else if ( key == "image" ) {
								$("#form-field-imageurl").val( val );
							} else if ( key == "date" ) {
								var date = new Date(val);
								var d = leading_zero( date.getDate() );
								var m = leading_zero( date.getMonth()+1 );
								var y = date.getFullYear();
								var hrs = leading_zero( date.getHours() );
								var min = leading_zero( round_minutes( date.getMinutes() ) );

								// var date = d.getDate() +"-"+ (d.getMonth()+1) +"-"+ d.getFullYear();
								// var time = d.getHours() +":00";
								// var time = d.getHours() +":"+ d.getMinutes();

								function leading_zero(i) {
									var o;
									if (i < 10) {
										o = "0"+ i;
									} else {
										o = i;
									}
									return o;
								}
								function round_minutes(i) {
									var round = 5 // round to 5 minutes
									var o = Math.floor(i / round) * round;
									return o;
								}

								$("#form-field-datetime_src-date").val( d +"-"+ m +"-"+ y );
								$("#form-field-datetime_src-time").val( hrs +":"+ min );
							}

						});

						$icon.css({
							'cursor': 'pointer',
							'pointer-events': 'auto'
						});
						$("body").css({'cursor':'default'});
					}).fail(function(jqXHR, textStatus){
						if(textStatus === 'timeout') {
							alert('Failed from timeout, please try again.\nIf this error occurs repeatedly, please check your internet connection, ensure that target url is valid and/or get in touch with admin.');
						}

						$icon.css({
							'cursor': 'pointer',
							'pointer-events': 'auto'
						});
						$("body").css({'cursor':'default'});
					});
			}

		});

	};

})(jQuery);