<div class="row rownumber{$RowNumber}">
	<% loop Columns %>
		<div class="large-{$Width} gridpos{$GridPos} $ExtraClasses columns">
			$Content
		</div>
	<% end_loop %>
</div>