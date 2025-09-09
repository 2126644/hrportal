fetch("/holidays")
    .then(response => response.text())
    .then(icsData => {
        let jcalData = ICAL.parse(icsData);
        let comp = new ICAL.Component(jcalData);
        let vevents = comp.getAllSubcomponents("vevent");

        let events = vevents.map((evt) => {
            let event = new ICAL.Event(evt);

            let start = event.startDate.toJSDate();
            let end = event.endDate.toJSDate();

            // Fix: if it's an all-day event, subtract 1 day from the end date
            if (event.startDate.isDate && event.endDate.isDate) {
                end.setDate(end.getDate() - 1);
            }

            return {
                startDate: start.toISOString(),
                endDate: end.toISOString(),
                summary: event.summary,
            };
        });

        // Initialize the simple calendar with holiday events
        $("#calendar-doctor").simpleCalendar({
            fixedStartDay: 0,
            disableEmptyDetails: true,
            events: events,
        });
    })
    .catch(err => console.error("Failed to load holidays:", err));
