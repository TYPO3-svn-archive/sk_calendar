README SNAPSHOT SKCALENDAR

Here you have a snapshot of the calendar, that means that some features might not be working as they are supposed to, others might be missing.

Especially missing is:

- codeoptimization wherever it is commented in the code
- Force Category Icons to be 10x10 pixel and .gif or .jpg (I assume this can be done in the $tca)
- Colorpicker is not picking colors
- Although you can assign usergroups they are ignored in frontend
- Richfeatured FE-Plugin. The existingone is just to get some output
- Dates > 100 seem to be a problem
- Count of the dates only count physical amounts (bug or feature?)
- No way to deleted exeptions so far

Please use sk_calendar@sitekick.de for feedback. Any comments are appriciated.

History:
-------------------------------------------------------------------
19.7.2004 
- initial release

20.7.2004 
- date must be required 
- switched to unix-time internaly xxxx-xx-xx format was stupid in the first place


Known Bugs:
- Duration is not a field with content. Trying to show Duration in extended view (list) gives an error
- JS error when editing recurring event
- System blows when trying to add a cat,target,organizer,location entry on a normal page Somehow the target sys-folder should be specified
- After editing an recurring event system does not automatically return to listview ($backpath?)
