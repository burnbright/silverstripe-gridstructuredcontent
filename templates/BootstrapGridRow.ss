<div class="row rownumber{$RowNumber}">
	<% loop Columns %>
		<div class="span{$Width} gridpos{$GridPos} $ExtraClasses">
			$Content
		</div>
	<% end_loop %>
</div>