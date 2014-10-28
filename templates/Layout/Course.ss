$CalendarWidget
$MonthJumper

<p><a href="$Parent.Link">&laquo; Back to $Parent.Title</a></p>
<div class="h-event">
  <h3 class="p-name">$Title</h3>
  <div class="p-summary">
  $Content
  </div>
  <% if OtherDates %>
  <div class="event-calendar-other-dates">
    <h4><% _t('CalendarEvent.ADDITIONALDATES','Additional Dates for this Event') %></h4>
    <ul>
      <% loop OtherDates %>
      <li><a href="$Link" title="$Event.Title">$DateRange<% if StartTime %> $TimeRange<% end_if %></a></li>
      <% end_loop %> 
    </ul>
  </div>
  <% end_if %>
</div>
<% if Image.ContentImage %>
			<img class="productImage" src="$Image.ContentImage.URL" alt="<% sprintf(_t("IMAGE","%s image"),$Title) %>" />
		<% else %>
			<div class="noimage"></div>
		<% end_if %>
  <% with CurrentDate %>
  
  <p class="dates">$DateRange<% if StartTime %> $TimeRange<% end_if %></p>
  <p class="u-url"><a href="$Link" >permalink</a></p>
  <p><a href="$ICSLink" title="<% _t('CalendarEvent.ADD','Add to Calendar') %>">Add this to Calendar</a></p>  
  <p>$Form</p>
<h5><a href="$RegisterLink">Register ($CalendarEvent.Cost)</a>  Tickets : $TicketsAvailable</h5>
<ul id="times">	
	<li>Course.Cost = $Course.Cost</li>
</ul>
  <% end_with %>

$RegistrationForm
$PageComments