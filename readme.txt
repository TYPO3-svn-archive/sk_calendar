README SNAPSHOT SKCALENDAR alpha - not even think about beta so fare

Here you have a snapshot of the calendar, that means that some features might not be working as they are supposed to, others might be missing.

Please use sk_calendar@sitekick.de for feedback. Any comments are appriciated.

History:
-------------------------------------------------------------------
11.9.2004
- Added first FE modules (weekview & weekbox) others to follow
- bugfixes & further optimising

20.7.2004 
- date must be required 
- switched to unix-time internaly xxxx-xx-xx format was stupid in the first place

19.7.2004 
- initial release

ToDo:
-------------------------------------------------------------------
- Duration is not a field with content. Trying to show Duration in extended view (list) gives an error, so do not do that
- JS error when editing recurring event
- System blows when trying to add a cat,target,organizer,location entry on a normal page Somehow the target sys-folder should be specified
- After editing an recurring event system does not automatically return to listview ($backpath?)
- codeoptimization wherever it is commented in the code
- Force Category Icons to be 10x10 pixel and .gif or .jpg (I assume this can be done in the $tca)
- Colorpicker is not picking colors
- Although you can assign usergroups they are ignored in frontend
- Dates > 100 seem to be a problem
- Count of the dates only count physical amounts (bug or feature?)
- Disable the creation of exeptions as item on page, but still show them readonly
- templatesystem for detailview (only!)