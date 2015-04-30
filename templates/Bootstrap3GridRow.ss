<div class="row rownumber{$RowNumber}">
	<% loop Columns %>
		<div class="col-md-{$Width} gridpos{$GridPos} $ExtraClasses">
			$Content
		</div>
	<% end_loop %>
</div>