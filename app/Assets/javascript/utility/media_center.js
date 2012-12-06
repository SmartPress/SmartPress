/**
 * 
 */
(function($) {
	
	$.Speedy.MediaCenter	= $.Speedy.Object.Extend({
		defaults	: {
			tabs		: '.media-tabs',			// Selector for tabs
			galleryLink	: '.media-gallery',			// Selector for galley link
			results		: '.media-results',			// Selector for media results panel
			editorLink	: '.media-edit',			// Selector for editor link button
			editorImage	: '.media-editor-image',	// Selector for media editor image
			prefix		: null,						// Potential Prefix for image update
			identifier	: '__media',				// Not sure why I added this
			libraryUri	: '/admin/uploads/images',	// Libray Uri
			resizeUri	: '/admin/uploads/image_resize/',	// Resize Uri
			uploadUri	: '/admin/uploads/add_image',		// Upload Uri
			imageBase	: '/media/',
			model		: 'Upload',
			disabledTabs: [2],
			bindLinksOnInit	: false,
			onUploadSuccess	: function(data) {}
		},
		
		ready: false,
		
		format: [ 
			'<div class="media-dialog" title="Media Editor">',
				'<div class="media-tabs">',
					'<ul>',
						'<li><a href="#{identifier}-upload-form">Upload</a></li>',
						'<li><a href="#{identifier}-media-library" class="media-gallery">Media Library</a></li>',
						'<li><a href="#{identifier}-media-editor" class="media-editor-link">Media Editor</a></li>',
					'</ul>',
					'<div id="{identifier}-upload-form">',
						'<form accept-charset="utf-8" action="{uploadUrl}" method="post" enctype="multipart/form-data" class="UploadAdminAddForm">',
							'<input type="hidden" value="POST" name="_method">',
							'<div class="input file"><label for="UploadFile">File</label><input type="file" class="UploadFile" name="data[{model}][file]"></div>',
							'<div class="submit">',
								'<input type="submit" value="Submit">',
								'<span class="media-upload-form-success"></span>',
							'</div>',
						'</form>',
					'</div>',
					'<div id="{identifier}-media-library" class="media-library">',
						'<div class="media-results"></div>',
						'<div class="media-results-pagination" class="ui-widget-header ui-corner-all" style="margin-top: 20px;"></div>',
					'</div>',
					'<div id="{identifier}-media-editor" class="media-editor">',
						'<div class="media-editor-image-cont">',
							'<img src="" class="media-editor-image" />',
						'</div>',
						'<div class="media-editor-details">',
							'<fieldset>',
								'<legend>Details</legend>',
								'<input type="hidden" name="data[Image][id]" />',
								'<ul>',
									'<li class="heading">Original</li>',
									'<li><label>Width:</label> <span class="media-editor-orig-width"></span></li>',
									'<li><label>Heigh:</label> <span class="media-editor-orig-height"></span></li>',
								'</ul>',
								'<ul>',
									'<li class="heading">Crop</li>',
									'<li><label>Width:</label> <input type="text" class="media-editor-crop-width" name="data[Image][crop][width]" value="0" /></li>',
									'<li><label>Height:</label> <input type="text" class="media-editor-crop-height" name="data[Image][crop][height]" value="0" />',
										'<input type="hidden" name="cropStartX" class="media-editor-crop-startx" value="" />',
										'<input type="hidden" name="cropStartY" class="media-editor-crop-starty" value="" />',
									'</li>',
									'<li class="heading">Scale</li>',
									'<li class="checkbox"><label>Lock</label> <input type="checkbox" name="lock" class="media-editor-scale-lock" checked="checked" /></li>',
									'<li><label>Width:</label> <input type="text" name="scaleWidth" class="media-editor-scale-width" /></li>',
									'<li><label>Height:</label> <input type="text" name="scaleHeight" class="media-editor-scale-height" /></li>',
								'</ul>',
								'<div class="submit">',
									'<input type="button" value="Save" class="media-editor-save-edited" />',
									'<span class="media-editor-save-edited-status"></span>',
								'</div>',
							'</fieldset>',
						'</div>',
					'</div>',
				'</div>',
			'</div>'
		],
		
		header	: [
			'<link rel="stylesheet" type="text/css" href="/css/imgareaselect/imgareaselect-default.css">',
			'<script src="/js/jquery.imgareaselect.pack.js" type="text/javascript"></script>',
			'<script src="/js/jquery.input-change.js" type="text/javascript"></script>'
		],
		
		initHead: function() {
			var content	= this.header.join("\n");
			$('head').append(content);
		},
		
		construct	: function(selector, options) {
			this.options = $.extend(this.defaults, options || {});
			
			if (!$.Speedy.MediaCenter.ready) {
				this.initHead();
				$.Speedy.MediaCenter.ready	= true;
			}
			
			if (!$.Speedy.MediaCenter.jqObj) {
				$.Speedy.MediaCenter.jqObj	= this.buildContainer();
			}
			
			this.jqObj	= $.Speedy.MediaCenter.jqObj;
			this.images	= null;
			this.currentEl 	= null;
			this.editingImage	= false;
			this.imgSelectorObj	= null;
			this.aspectRatio	= null;
			this.currentRequest	= null;
			this.identifier	= this.options.identifier;
			this.openLink	= $(selector);
			//this.ajaxLoader	= $.ajaxLoader.getInstance();
			this.libraryUrl	= this.options.libraryUri;
			this.resizeUrl	= this.options.resizeUri; 
			this.uploadUrl	= this.options.uploadUri;
			this.model		= this.options.model;
			
			if (this.options.prefix) this.libraryUrl +=  '/' + this.options.prefix;
			
			$(this.jqObj).hide();
			$(this.jqObj).append(this.getTpl());
			this.initialize();
			
			if (this.options.bindLinksOnInit) {
				this.bindEditorLinks();
			}
		},
		
		getTpl	: function() {
			var tpl	= this.format.join("\n");
			tpl	= tpl.replace(/{identifier}/ig, this.identifier);
			tpl	= tpl.replace(/{uploadUrl}/ig, this.uploadUrl);
			tpl	= tpl.replace(/{model}/ig, this.model);
			
			return tpl;
		},
		
		onOpenLinkClick	: function(el) {
			var $this	= this;
			this.currentEl = $($(el).attr('rel'));
			 	 			
			this.window = this.jqObj.dialog({
				width: 605,
				modal: false,
				close: function(event, ui) {
					if ($this.imgSelectorObj != null) {
						$this.resetImgSelector();
					}
				},
				open: function(event, ui) {
					if (!$this.imgSelectorObj && $this.editingImage == true) {
						$this.initImgSelector();
					}
				}
			});
		},
		
		onTabsShow: function(event, ui) {
			var $this	= this;
			if (ui.index == 2) {
				if (!$this.imgSelectorObj && $this.editingImage == true) {
					$this.initImgSelector();
				}
			} else if ($this.imgSelectorObj != null) {
				$this.resetImgSelector();
			}
		},
		
		initialize: function() {
			var $this = this;
			
			// Bind open media buttons
			$this.openLink.click(function() {
				$this.onOpenLinkClick(this);
			});
			
			$($this.options.galleryLink).click(function() { 
				$this.onGalleryLinkClick(); 
			});
			
			// Start tabs for dialog prompt
			$($this.options.tabs).tabs({
				disabled: $this.options.disabledTabs,
				show: function(event, ui) {
					$this.onTabsShow(event, ui);
				}
			});	
			
			// Bind actions on media editor scale width
			$('.media-editor-scale-width', $this.jqObj).inputChange({
				keydown: function(event, jqObj) {
					if (event.keyCode == 38) {
						jqObj.val(parseInt(jqObj.val()) + 1);
						
						if ($('.media-editor-scale-lock', $this.jqObj).is(':checked')) {
							alert($('.media-editor-scale-height', $this.jqObj).val());
							$('.media-editor-scale-height', $this.jqObj).val(parseInt($('.media-editor-scale-height', $this.jqObj).val()) + 1);
						}
					} else if (event.keyCode == 40) {
						jqObj.val(parseInt(jqObj.val()) - 1);
						if ($('.media-editor-scale-lock', $this.jqObj).is(':checked')) {
							$('.media-editor-scale-height', $this.jqObj).val(parseInt($('.media-editor-scale-height', $this.jqObj).val()) - 1);
						}
					}
				},
				timeout: 1000,
				callback: function(event, jqObj) {
					$($this.options.editorImage, $this.jqObj).attr('width', jqObj.val());
					
					if ($('.media-editor-scale-lock', $this.jqObj).is(':checked')) {
						var height = parseInt(parseInt(jqObj.val())/$this.aspectRatio);
						$($this.options.editorImage, $this.jqObj).attr('height',height);
						$('.media-editor-scale-height', $this.jqObj).val(height);
					}
					
					$this.resetImgSelector();
					$this.initImgSelector();
				}
			});
			
			// Bind actions on media editor scale height
			$('.media-editor-scale-height', $this.jqObj).inputChange({
				keydown: function(event, jqObj) {
					if (event.keyCode == 38) {
						jqObj.val(jqObj.val() + 1);
						if ($('.media-editor-scale-lock', $this.jqObj).is(':checked')) {
							$('.media-editor-scale-height', $this.jqObj).val(parseInt($('.media-editor-scale-height', $this.jqObj).val()) + 1);
						}
					} else if (event.keyCode == 40) {
						jqObj.val(jqObj.val() - 1);
						if ($('.media-editor-scale-lock', $this.jqObj).is(':checked')) {
							$('.media-editor-scale-height', $this.jqObj).val(parseInt($('.media-editor-scale-height', $this.jqObj).val()) - 1);
						}
					}
				},
				timeout: 1000,
				callback: function(event, jqObj) {
					$($this.options.editorImage, $this.jqObj).attr('height', jqObj.val());
					
					if ($('.media-editor-scale-lock', $this.jqObj).is(':checked')) {
						var width = parseInt(parseInt(jqObj.val()) * $this.aspectRatio);
						$($this.options.editorImage, $this.jqObj).attr('width',width);
						$('.media-editor-scale-width', $this.jqObj).val(width);
					}
					
					$this.resetImgSelector();
					$this.initImgSelector();
				}
			});
			
			// Bind actions to media uploader
			$('form', this.jqObj).submit(function() {
				return $this.onUploadFormSubmit(this);
 	 		});
		},
		
		onUploadFormSubmit	: function(form) {
			var $this	= this;
			if ($this.options.prefix) {
				form.action	+= '/' + this.options.prefix;
			}
			
			$('span.media-upload-form-success', this.jqObj).text('');
			$(form).ajaxSubmit({
				beforeSubmit : function(formData,jqForm,options) { 
					options.dataType	= 'json';
					options.url	+= '/response:json';
				},
				success: function(responseText, statusText, xhr, $form) {
					console.log(responseText);
					if (responseText.success) {
						$('span.media-upload-form-success', $this.jqObj).text('Success');
						
						$this.options.onUploadSuccess(responseText.data);
					} else {
						$('span.media-upload-form-success', $this.jqObj).html('Failure: ' + responseText.error);
					}
				}
			});

			return false;
		},
		
		resetImgSelector: function() {
			this.imgSelectorObj.remove();
			$(this.options.editorImage, this.jqObj).removeData('imgAreaSelect');

			this.imgSelectorObj = null;
		},
		
		initImgSelector: function() {
			var $this	= this;
			$('.media-editor-crop-width', this.jqObj).val('0');
			$('.media-editor-crop-height', this.jqObj).val('0');
			$('.media-editor-crop-startx', this.jqObj).val('0');
			$('.media-editor-crop-starty', this.jqObj).val('0');
			
			this.imgSelectorObj	= $(this.options.editorImage, this.jqObj).imgAreaSelect({
				handles: true,
				instance: true,
				onSelectChange: function(img, selection) {
					$('.media-editor-crop-width', $this.jqObj).val(selection.width);
					$('.media-editor-crop-height', $this.jqObj).val(selection.height);
					$('.media-editor-crop-startx', $this.jqObj).val(selection.x1);
					$('.media-editor-crop-starty', $this.jqObj).val(selection.y1);
				}
			});
		},
		
		setImage: function(src, onLoad) {
			delete this.editImage;
			
			this.editImage	= new Image();
			this.editImage.src = src;
			if (onLoad) {
				this.editImage.onload		= onLoad
			} 
			
			return this;
		},
		
		getImage: function() {
			return this.editImage;
		},
		
		openEditor: function(obj) {
			this.objData	= $(obj).data();
			
			var refferer	= $(obj),		// get refferer to obtain the image src
				imgObj	= $('img.media-library-image:eq(' + this.objData.index + ')'),
				imgData	= imgObj.data(),
				index 	= this.objData.index,
				$this	= this,
				width	= this.getImage().naturalWidth,
				height	= this.getImage().naturalHeight;
				
			$(this.options.tabs, this.jqObj).tabs('enable', 2);
			$(this.options.tabs, this.jqObj).tabs('select', 2);
			
			$(this.options.editorImage, this.jqObj).attr('src', this.getImage().src);
			
			if (this.imgSelectorObj == null) {
				this.initImgSelector();
			} else {
				this.imgSelectorObj.update();
			}
			
			$('.media-editor-image', this.jqObj)
				.attr('width', 	width)
				.attr('height',	height);
			$('.media-editor-orig-width',	$this.jqObj).text(width);
			$('.media-editor-orig-height', 	$this.jqObj).text(height);
			$('.media-editor-scale-width', 	$this.jqObj).val(width);
			$('.media-editor-scale-height',	$this.jqObj).val(height);
			
			this.aspectRatio	= parseFloat(width/height);
			this.editingImage	= true;
			
			
			$('.media-editor-save-edited', $this.jqObj).unbind('click');
			$('.media-editor-save-edited', $this.jqObj).click(function() {
				var request;
				
				if ($this.currentRequest) $this.currentRequest.abort();	
			
				request	= $this.resizeUrl + '/' + $this.objData.id;
				if ($this.options.prefix) request += '/' + $this.options.prefix;
				request += '/response:json';
				
				//$this.showLoader();
				$('.media-editor-save-edited-status', $this.jqObj).text('');
				console.log(request);
				$this.currentRequest = $.ajax({
					url: request,
					dataType: 'json',
					data: {
						cropWidth	: $('.media-editor-crop-width', $this.jqObj).val(),
						cropHeight	: $('.media-editor-crop-height', $this.jqObj).val(),
						scaleWidth	: $('.media-editor-scale-width', $this.jqObj).val(),
						scaleHeight	: $('.media-editor-scale-height', $this.jqObj).val(),
						startX	: $('.media-editor-crop-startx', $this.jqObj).val(),
						startY	: $('.media-editor-crop-starty', $this.jqObj).val()
					},
					type: 'POST',
					success: function(data, textStatus, jqXHR) {
						console.log(data);
						if (data.success) {
							$('.media-editor-save-edited-status', $this.jqObj).text('Saved!');
						} else {
							$('.media-editor-save-edited-status', $this.jqObj).text('Encountered an error while trying to save');
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log([textStatus, jqXHR, errorThrown]);
					}
				});
				//$this.hideLoader();
				
				$this.currentRequest	= null;
			});
		},
		
		onGalleryLinkClick: function() {
		 	var i, newEntry, images, request;
		 	var $this	= this; 
		 	var editorLink = this.options.editorLink.substring(1);
			var tpl	= ['<div class="image-thumb">',
						  '<a href="javascript:void(0)" class="media-library-image-link"><img src="{$src}" width="120" height="80" border="0" class="media-library-image" /></a><br/>',
						  '<a href="javascript:void(0)" class="media-editor-link ' + editorLink + '" data-img="/media/{$src}" data-id="{$id}">Send to Editor</a><br />',
						  '<a href="javascript:void(0)" class="media-editor-delete-link" index="{$index}">Delete</a>',
						  '<ul>',
						  	'<li>Width: {$width}</li>',
						  	'<li>Height: {$height}</li>',
						  '</ul>',
						'</div>'
					].join("\n");
		 	 	 	
			if (this.currentRequest) this.currentRequest.abort(); 
			request	= this.libraryUrl + '/response:json';
				
			//this.showLoader();
			this.currentRequest = $.ajax({
				url: request,
				dataType: 'json',
				success: function(data, textStatus, jqXHR) {
					console.log(data);
					if (data.success) {
						$($this.options.results).html('');
						images	= data.images;
						$this.images	= images;
							
						for (i=0; i < images.length; i++) {
							newEntry	= tpl.replace(/\{\$src\}/ig, $this.options.imageBase + images[i][$this.model].name);
							newEntry	= newEntry.replace(/\{\$width\}/ig, images[i][$this.model].info.width);
							newEntry	= newEntry.replace(/\{\$height\}/ig, images[i][$this.model].info.height);
							newEntry	= newEntry.replace(/\{\$id\}/ig, images[i][$this.model].id);
							$($this.options.results).append(newEntry);
							$('.media-library-image:last', $this.jqObj).data('image', images[i].Upload);
							$('.media-editor-link:last', $this.jqObj).data('index', i);
							$('.media-editor-delete-link:last', $this.jqObj).data('index', i);
						}
						
						$('.media-results-pagination', $this.jqObj)
							.html('')
							.append(data.paginate.prev)
							.append(data.paginate.numbers)
							.append(data.paginate.next);
						
						$this.bindEditorLinks();
					}
				}
			}); 
			//this.hideLoader();
			
			this.currentRequest	= null;
		},
		
		onEditorLinkClick: function(link) {
			var self	= this;
			
			this.setImage($(link).data('img'), function() {
				self.onOpenLinkClick.apply(self, [link]);
				self.openEditor.apply(self, [link]);
			});
		},
		
		bindEditorLinks: function() {
			var $this = this;
			
			$(this.options.editorLink).click(function(e) {
				$this.onEditorLinkClick(e.currentTarget);
			});
			
			$('a.media-library-image-link', $this.jqObj).click(function() {
				$this.populateImageName(this);
			});
			
			$('a.media-editor-delete-link', $this.jqObj).click(function() {
				$this.deleteImage(this);
			});
			
			$('span.media-library-prev', this.jqObj)
				.button()
				.click(function() {
					$this.libraryUrl	= $('a:eq(0)', $(this)).attr('href');
					$this.initMedia();
					return false;
				});
			
			if ($('span.media-library-prev', this.jqObj).hasClass('disabled')) 
				$('span.media-library-prev', this.jqObj).button( 'disable' );
			
			$('span.media-library-next', this.jqObj)
				.button()
				.click(function() {
					$this.libraryUrl	= $('a:eq(0)', $(this)).attr('href');
					$this.initMedia();
					return false;
				});
			if ($('span.media-library-next', this.jqObj).hasClass('disabled')) 
				$('span.media-library-next', this.jqObj).button( 'disable' );
			
			
			$('span.media-library-pages', this.jqObj).buttonset();
			$('a.media-library-page', this.jqObj)
				.button()
					.click(function() {
						$this.libraryUrl	= $(this).attr('href');
						$this.initMedia();
						return false;
					});
			$('span.current', $('span.media-library-pages', this.jqObj)).button({ disabled: true });
			
			
		},
		
		showLoader: function() {
			this.window.dialog('close');
			//this.ajaxLoader.open().toTop();
		},
		
		hideLoader: function() {
			//this.ajaxLoader.close();
			this.window.dialog('open');
		},
		
		populateImageName: function(el) {
		 	var src;
			if (this.currentEl == null) return false;

			src	= $(el).children('img').attr('src').substring(1);
			this.currentEl.val(src);
			this.jqObj.dialog('close');
			return true;
		},
		
		deleteImage: function(el) {
			var index	= $(el).attr('index');
			var elData	= $(el).data(); 
			var imgObj	= $('img.media-library-image:eq(' + elData.index + ')');
			var imgData	= imgObj.data();
			var $this	= this;
			
			if (this.currentRequest) this.currentRequest.abort();
			
			//this.showLoader();
			this.currentRequest = $.ajax({
				url: '/admin/uploads/delete/' + imgData.image.id + '/request:json',
				dataType: 'json',
				success: function(data, textStatus, jqXHR) {
					if (data.success) {
						$('div.image-thumb:eq(' + elData.index + ')', $this.jqObj).remove();
					}
				}
			}); 
			//this.hideLoader();
			
			this.currentRequest	= null;
		},
		
		buildContainer	: function() {
			var container	= document.createElement('div');
			container.setAttribute('class', 'media-holder');
			
			$('body').append(container);
			return $(container);
		}
		
	});
	
	/*$.mediaCenter = {
		objs: new Array(),
		
		options: {
			openLink	: '.media-link',
			tabs		: '.media-tabs',
			galleryLink	: '.media-gallery',
			results		: '.media-results',
			editorLink	: '.media-edit',
			editorImage	: '.media-editor-image',
			prefix		: null,
			identifier	: null
		},
		
		init: function(params, jqObj) {
			var options = $.extend($.mediaCenter.options, params || {});
			
			if (!options.identifier) {
				options.identifier	= '__media-' + $.mediaCenter.objs.length;
			}
			
			if ($.mediaCenter.objs.length < 1) {
				$.Speedy.MediaCenter.initHead();
			}
	
			jqObj	= (!jqObj) ? 
				$.Speedy.MediaCenter.buildContainer() : $(jqObj.selector +':first');	
			return $.mediaCenter.objs.push(
				 $.Speedy.MediaCenter.Create(jqObj, options)
			);
		}
	}*/
	
	$.fn.mediaCenter = function(params) {
		return this.each(function() {
    	 	return $.Speedy.MediaCenter.Create(this, params); 
    	});
	}
})(jQuery);

 		


