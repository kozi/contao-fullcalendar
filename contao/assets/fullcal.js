function initializeFullcal(id, eventSourcesById, fcOptions, tooltipOpts, additionalOpts) {
    var fc, fcContainer = document.getElementById("calendar_" + id),
        fcMenuElement = document.getElementById("fullcal_menu_" + id),
        dots = fcMenuElement && fcMenuElement.getElementsByClassName("fullcal_menu_dot"),
        activeClass = "fullcal_menu_active";

    dots && Array.prototype.forEach.call(dots, function (dot) {
        var eventSource, el = dot.parentElement,
            eventSourceId = el.id.replace("calAlias_", "");
        dot.addEventListener("click", function (event) {
            if (el.classList.contains(activeClass)) {
                el.classList.remove(activeClass);
                eventSource = fc.getEventSourceById(eventSourceId);
                eventSource && eventSource.remove();
            } else {
                el.classList.add(activeClass);
                fc.addEventSource(eventSourcesById[eventSourceId]);
            }
        });
    });

    if (tooltipOpts) {
        tooltipOpts.allowHTML = true;
        fcOptions.eventDidMount = function (info) {
            if (info.event && info.event._def && info.event._def.extendedProps && info.event._def.extendedProps.description) {
                tooltipOpts.content = info.event._def.extendedProps.description;
                tippy(info.el, tooltipOpts);
            }
        }
    }

    fcOptions.eventSources = Object.keys(eventSourcesById).map(function (id) {
        return eventSourcesById[id];
    });

    additionalOpts && Object.keys(additionalOpts).forEach(function (key) {
        fcOptions[key] = additionalOpts[key];
    });

    fc = new FullCalendar.Calendar(fcContainer, fcOptions);
    fc.render();
}
