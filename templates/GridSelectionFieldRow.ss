<div class="row">
	<% loop Columns %>
		<div class="span{$Width}">
			<% if IsHolder %>
				<input type="radio" value="$GridPos" name="GridPosition" <% if GridPos = 0 %>checked<% end_if %>/>
			<% else %>
				$Content
			<% end_if %>
		</div>
	<% end_loop %>
</div>