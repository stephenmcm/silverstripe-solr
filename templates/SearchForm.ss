<form $FormAttributes>
<% if $Message %>
<p id="{$FormName}_error" class="message $MessageType">$Message</p>
<% else %>
<p id="{$FormName}_error" class="message $MessageType" style="display: none"></p>
<% end_if %>
<fieldset>
	<% loop $Fields %>
	<% if $Name = 'Search' %>
	<div id="$Name" class="field<% if $extraClass %> $extraClass<% end_if %>">
		<% if $Title %><label class="left" for="$ID">$Title</label><% end_if %>
		<div class="middleColumn">
			$Field
		<% if $RightTitle %><label class="right" for="$ID">$RightTitle</label><% end_if %>
		<% if $Message %><span class="message $MessageType">$Message</span><% end_if %>
		<% if $Description %><span class="description">$Description</span><% end_if %>
			<div class="esp-dropdown esp-search-suggestions jq-dropdown" aria-labelledby="SearchForm_getForm_Search">
				<div class="recent-searches panel panel-default" style="display: none">
					<div class="panel-heading">
						<h3 class="panel-title">Recent Searches</h3>
					</div>
					<ul class="list-group recent-searches-list">
						<li class="recentsearch list-group-item small"><a href'#'>Title</a></li>
					</ul>
				</div>
				<% if $Top.Controller.SearchSuggestions.Count %>
				<div class="panel-heading">
					<h3 class="panel-title">Search Suggestions</h3>
				</div>
				<ul class="list-group">
					<% loop $Top.Controller.SearchSuggestions %>
					<li class="list-group-item small">
						<a href="#">$Term</a>
					</li>
					<% end_loop %>
				</ul>
				<% end_if %>
			</div>
		</div>
	</div>
	<% else %>
		$FieldHolder
	<% end_if %>
	<% end_loop %>
	<% loop $Actions %>
	$Field
	<% end_loop %>
</fieldset>
</form>