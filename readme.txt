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

XX.XX.XXXX
- Switchable BE-Modes (data from samepage or specified sysfolder)
- Listview
- Textsearch events
- Switch Warnings via TS
- fix VCE-Editmode
- minor changes

To Do:
-------------------------------------------------------------------
- Duration is not a field with content. Trying to show Duration in extended view (list) gives an error, so do not do that
- JS error when editing recurring event
- After editing an recurring event system does not automatically return to listview ($backpath?)
- codeoptimization wherever it is commented in the code
- Force Category Icons to be 10x10 pixel and .gif or .jpg (I assume this can be done in the $tca)
- Colorpicker is not picking colors
- Although you can assign usergroups they are ignored in frontend
- Dates > 100 seem to be a problem
- Count of the dates only count physical amounts (bug or feature?)
- templatesystem for sk_calendar
- Multiple Select
- yearview should not base on htmlview since it generates a pdf
- Additional Holidays via TS