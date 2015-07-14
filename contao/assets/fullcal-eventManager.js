EventManager = function(calendarId, arrEvents) {
    this.$jqueryEl          = $(calendarId);
    this.arrCurrentCalendar = [];
    this.arrAllEvents       = arrEvents;
    this.arrFilteredEvents  = arrEvents;
};

EventManager.prototype = {
    getCurrentEvents : function() {
        return this.arrFilteredEvents;
    },
    filterByCalendar : function() {
        this.$jqueryEl.fullCalendar('removeEventSource', this.arrFilteredEvents);

        this.arrFilteredEvents = [];
        for (var i=0,  tot=this.arrAllEvents.length; i < tot; i++) {
            var event = this.arrAllEvents[i];
            if ($.inArray(event.calendarAlias, this.arrCurrentCalendar) != -1) {
                this.arrFilteredEvents.push(event);
            }
        }
        this.$jqueryEl.fullCalendar('addEventSource', this.arrFilteredEvents);
    },
    update : function(element) {

        $(element).toggleClass('icon-check-empty icon-check');
        $(element.parentElement).toggleClass('active inactive');

        var newArray= [];
        $('li.active', '.fullcal_menu').each(function() {
            console.log(this, this.id);
            newArray.push(this.id.replace('calAlias_', ''));
        });
        this.arrCurrentCalendar = newArray;
        this.filterByCalendar();
    }
};
