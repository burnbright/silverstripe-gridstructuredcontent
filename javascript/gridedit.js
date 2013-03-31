(function($) {

	$.entwine("ss",function($){
	
		var contentparts = [];
		var currentcontent = 0;

		$("#GridContent textarea").entwine({
			delimiter: function(){
				return this.attr("data-gscdelimiter");
			},
			oneditorinit: function(){
				currentcontent = 0;
				this.loadparts($("#Form_EditForm_Content").val());
				this._super();
				this.retrievepart(currentcontent);
			},
			retrievepart: function(pos){
				var ed = this.getEditor();
				if(contentparts[pos]){
					ed.setContent(contentparts[pos]);
				}else{
					ed.setContent("");
				}
				//TODO: ignore save/publish dirty bit for GridContent & GridPos, but do monitor the array?
			},
			storepart: function(pos){
				var ed = this.getEditor();
				contentparts[pos] = ed.getContent();
			},
			loadparts: function(content){
				if(content !== "") {
					contentparts = content.split(this.delimiter());
				}
			},
			saveparts: function(){
				var ed = this.getEditor();
				$("#Form_EditForm_Content").val(contentparts.join(this.delimiter()));
			},
			'from .cms-edit-form': {
				onbeforesubmitform: function(e) {
					this.storepart(currentcontent);
					this.saveparts();
					contentparts = [];
					this._super(e);
				}
			}
			
		});
		
		$('#GridPos input').entwine({
			onchange: function(){
				$("#GridContent textarea").storepart(currentcontent);
				currentcontent = $('#GridPos input:checked').val();
				$("#GridContent textarea").retrievepart(currentcontent);
			}
		});		

	});
	
})(jQuery);