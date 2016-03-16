$(function(){
	function buttonMore(obj, event, key)
	{
		obj.execCommand('insertHtml', '[Lire la suite]');
	}

	$('.delete').click(function(){
		return confirm("Souhaitez-vous vraiment supprimer cette news ?");
	});

	$('.conseilHandler').click(function() {
		$(this).parent().find('div').toggle('slow');
		$(this).parent().find('ul').toggle('slow');
	});

	$('#listSelection').change(function() {
		$('#listSelected').val($('#listSelection>option:selected').text());
	});

	$.datepicker.setDefaults($.datepicker.regional.fr);
	$('#date_start').datepicker({'dateFormat': 'dd/mm/yy'});
	$('#date_end').datepicker({'dateFormat': 'dd/mm/yy'});

	var buttonsPage = ['html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', '|',
	                   'fontcolor', 'backcolor', '|',
	                   'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
	                   'alignleft', 'aligncenter', 'alignright', 'justify', '|',
	                   'link', 'image', 'file', 'video'];
	$('.editorPage').redactor({
		lang: 'fr',
		imageUpload: 'upload-picture.html',
		fileUpload: 'upload-file.html',
		imageGetJson: 'galery.json',
		minHeight: '200',
		buttons: buttonsPage
	});

	var buttonsNews = ['html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', '|',
	                   'fontcolor', 'backcolor', '|',
	                   'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
	                   'alignleft', 'aligncenter', 'alignright', 'justify', '|',
	                   'link', 'image', 'file', 'video',  '|',
	                   'more'];
	$('.editorNews').redactor({
		lang: 'fr',
		imageUpload: 'upload-picture.html',
		fileUpload: 'upload-file.html',
		imageGetJson: 'galery.json',
		minHeight: '200',
		buttons: buttonsNews,
		buttonsCustom: {
			more: {
				title: 'More',
				callback: buttonMore
			}
		}  
	});
});
