Builds a complete HTML calendar for the given year, with a link to create a new page for each day of the year. Existing pages will have a tooltip showing a preview of the page content.
----
Parameters:
1. ns=<namespace> (defaults: current ns)
2. year=YYYY : build calendar for this year (default: this year)
3. name=<name> : prefix for new page name, e.g diary, journal, day [default]
4. size=?? : font size to use; this controls the width/height of the calendar table (default:12px)
----
E.g.: {{yearbox>year=2010;name=journal;size=12;ns=diary}}