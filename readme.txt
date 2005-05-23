README SNAPSHOT SKCALENDAR alpha - not even think about beta so far

Here you have a snapshot of the calendar, that means that some features might not be working as they are supposed to, others might be missing.

Special Thanks to:
Jan Wischnat - for bugging me with BE-Conformity ("There has to be a way to do this with normal BE-Functions"). 
Sven Wilhelm - for explaining the concept of abstract PHP-Classes to me by using colored candy as an example.
Nils Teller - for having always an open ear / chatwindow for imminent problems.
All Sponsors that helped developing sk_calendar
All People testing and giving feedback to the calendar
Everyone I forgot.

Please use sk_calendar@sitekick.de for feedback. Any comments are appriciated.

<DEDICATION>

This piece of software is dedicated to my son Daniel Alexander Biberger who was first released (=born) on the very same day like sk_calendar. I hope that both will florish and make a difference in peoples lives, although I think that Daniel will have more posibilities to do so :).

Tettnang, Germany
19. October 2004

</DEDICATION>

History:
-------------------------------------------------------------------
19.10.2004
- First release to the Public

28.10.2004 0.1.0
- First Upload to TER after raising the uploadquota (thanks robert)

09.11.2004 0.1.1
- Fix display of times
- change CE to dropdowns rather than checkboxes
- banned exeptions from normal pages (view only)
- exeptions did not work when saving & closing
- minor fixes
- fix in filter (organizer now work)
- finish and dutch translation

01.02.2004
- Switchable BE-Modes (data from same page or specified sysfolder)
- smart TEMPLATE-SYSTEM (!)
- Flexforms
- Listview
- Archive
- Upcoming Events
- Textsearch events
- Switch Warnings via TS
- fix VCE-Editmode
- minor fixes

04.04.2005 0.2.1
- Fixed Problem with PDF parsing
- Fix Sorting - thanks Rainer
- Fixed amount of weeks in month-view
- Filters now switchable, got rid of warning-panel in FF
- German Documentation (!)
- minor fixes regarding 3.7 compatibility

To Do:
-------------------------------------------------------------------
- Duration is not a field with content. Trying to show Duration in extended view (list) gives an error, so do not do that
- JS error when editing recurring event
- After editing an recurring event system does not automatically return to listview ($backpath?)
- codeoptimization wherever it is commented in the code
- Force Category Icons to be 10x10 pixel and .gif or .jpg (I assume this can be done in the $tca)
- Colorpicker is not picking colors ($tca, again?)
- Although you can assign usergroups they are ignored in frontend
- Dates > 100 seem to be a problem
- Count of the dates only count physical amounts (bug or feature?)
- Multiple Select
- yearview should not base on htmlview since it generates a pdf
- more PDF views (3 Months a page, etc.)
- Additional Holidays via TS
- Why is TStamp of exeptionsdates not decoded? ($TCA Someplace)
- Get rid of Errors in EM-Overview
- DBAL-Functions rather than MYSQL-Calls (sorry for that)
- Extend Organizer & Location with more fields
- Make events searchable by indexed_search
