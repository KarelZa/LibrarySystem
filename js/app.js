$(document).ready(function () {
	// Submit form data via Ajax
	$('#bookForm').on('submit', function (e) {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'addBook.php',
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function () {
				$('#submit').attr('disabled', 'disabled');
				setTimeout(function () {
					$('[type="submit"]').prop('disabled', false);
				}, 2000); // 2 seconds
			},
			success: function (response) {
				//console.log(response);
				// $('.form-message').html('');
				if (response.status == 1) {
					$('#inputFirstName, #inputSurname,#inputTitle,#inputDescription,#inputYear,#ISBN').removeClass('input-error');
					TogggleMessageDiv(2000);
					$('#bookForm')[0].reset();
					$('.form-message')
						.html(
							'<p class="fs-3 py-4 text-center position-absolute col-12 bg-success text-light rounded-3 m-0 display-5">' +
								response.message +
								'</p>'
						)
						.fadeIn('fast')
						.delay(2000)
						.fadeOut();
				} else if (response.status == 2) {
					showError('#inputYear', response.message, 2000);
				} else if (response.status == 3) {
					showError('#ISBN', response.message, 2000);
				} else {
					showError(
						'#inputFirstName, #inputSurname,#inputTitle,#inputDescription,#inputYear,#ISBN',
						response.message,
						2000
					);
				}
			},
		});
	});
});

// Display Message Div that is removed after 2s
function TogggleMessageDiv(msTimer) {
	$('#inner').after('<div class="form-message"></div>');
	setTimeout(function () {
		$('.form-message').remove();
	}, msTimer);
}

// Display Error Message and indicate wrong inputs
function showError(elements, ErrorMessage, timeInMS) {
	$(elements).addClass('input-error');
	setTimeout(function () {
		$(elements).removeClass('input-error');
	}, 2000);
	// Add and Remove MessageDiv
	TogggleMessageDiv(timeInMS);
	$('.form-message')
		.html(
			'<p class="fs-3 py-4 text-center position-absolute col-12 bg-danger text-light rounded-3 m-0 display-5">' +
				ErrorMessage +
				' </p>'
		)
		.fadeIn('fast')
		.delay(timeInMS)
		.fadeOut();
}

// Client-side File type validation
$('#bookCover').change(function () {
	let file = this.files[0];
	let fileType = file.type;
	let fileSize = file.size;
	const match = ['image/jpeg', 'image/png', 'image/jpg'];
	if (!(fileType == match[0] || fileType == match[1] || fileType == match[2])) {
		//alert('❌ Sorry, only JPG, JPEG, & PNG files are allowed to upload.');
		$('#bookCover').val('');
		showError('', '❌ Sorry, only JPG, JPEG, & PNG files are allowed to upload.', 2600);
		return false;
	}
	if (fileSize > 2000000) {
		$('#bookCover').val('');
		showError('', '❌ Image is too large (max size 2MB)', 2000);
		$('#bookCover').attr('disabled', 'disabled').css('opacity', '.8');
		setTimeout(function () {
			$('[type="file"]').prop('disabled', false).css('opacity', '');
		}, 2000); // 2 seconds
		return false;
	}
});

$(function () {
	$('.dropdown-menu li a').click(function () {
		//$('.btn.dropdown-toggle').text($(this).text());
		$('#toggle').text($(this).text());
	});
	$('#show').click(function () {
		var show = $('#toggle').text();
		alert(show);
	});
});
