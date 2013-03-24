(function($) {

	$.entwine("ss",function($){
	
		var delimiter = "<p>[SPLIT]</p>"; //TODO: load this from php config
		var contentparts = [];
		var currentcontent = 0;
		
		//split all Content into contentparts array
		var content = $("#Form_EditForm_Content").val();
		if(content !== "") {
			contentparts = content.split(delimiter);
		}

		$("#GridContent textarea").entwine({
			oneditorinit: function(){
				currentcontent = 0;
				this._super();
				this.loadpart(currentcontent);
			},
			loadpart: function(pos){
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
			saveparts: function(){
				var ed = this.getEditor();
				$("#Form_EditForm_Content").val(contentparts.join(delimiter));
			},
			'from .cms-edit-form': {
				onbeforesubmitform: function(e) {
					this.storepart(currentcontent);
					this.saveparts();
					this._super(e);
				}
			}
			
		});
		
		$('#GridPos select').entwine({
			onchange: function(){
				$("#GridContent textarea").storepart(currentcontent);
				currentcontent = $('#GridPos select').val();
				$("#GridContent textarea").loadpart(currentcontent);
			}
		});

	});
	
})(jQuery);