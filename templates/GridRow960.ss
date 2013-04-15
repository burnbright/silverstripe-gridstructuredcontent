<div class="row-{$RowNumber} $ExtraClasses">
	<% loop Columns %>
		<div class="grid_{$Width} section-{$GridPos}<% if ExtraClasses %> $ExtraClasses<% end_if %><% if First %> alpha<% end_if %><% if Last %> omega<% end_if %>">
			$Content
		</div>
	<% end_loop %>
	<div class="clear"></div>
</div>