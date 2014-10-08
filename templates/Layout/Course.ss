$CalendarWidget
$MonthJumper

<p><a href="$Parent.Link">&laquo; Back to $Parent.Title</a></p>
<div class="vevent">
  <h3 class="summary">$Title</h3>
<% if Image.ContentImage %>
			<img class="productImage" src="$Image.ContentImage.URL" alt="<% sprintf(_t("IMAGE","%s image"),$Title) %>" />
		<% else %>
			<div class="noimage">no image</div>
		<% end_if %>
  <% with CurrentDate %>
  <p class="dates">$DateRange<% if StartTime %> $TimeRange<% end_if %></p>
  <p><a href="$ICSLink" title="<% _t('CalendarEvent.ADD','Add to Calendar') %>">Add this to Calendar</a></p>
  <p><a href="$AddToCartLink" title='Add to Cart'>Add to Cart</a></p>
  <p><a href="$OrderLink" title='Add to Cart'>Order</a></p>
  <p>
  I need to get a Form in here</p>
  <p>$Form</p>
<h5><a href="$RegisterLink">Register ($CalendarEvent.Cost)</a>  Tickets : $TicketsAvailable</h5>
<ul id="times">	
	<li>Course.Cost = $Course.Cost</li>
</ul>
  <% end_with %>
  
  $Content
  
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
$RegistrationForm
$PageComments