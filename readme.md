Overview
This release marks a complete rewrite of the module's code. The initial approach, which involved grabbing each element and placing them in a custom table layout, has been replaced with a more efficient solution using CSS.

Key Changes
New CSS-based Layout:

Elements are now positioned using a flex grid pattern for better flexibility and responsiveness.
This replaces the previous method of using a custom table layout.
Action Tag Compatibility:

Previous issues with REDCap’s Action Tags prompted the shift to this new layout.
The module now works seamlessly with existing REDCap action tags.

README Reformat:
The README file has been reformatted from HTML to PHP.
It now includes options for superusers to download:
A test form zip file
A data dictionary
These will make it easier to test the module's functionality and compatibility with common REDCap action tags, particularly after REDCap updates.
