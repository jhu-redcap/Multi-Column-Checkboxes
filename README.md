# Multi-Column-Checkboxes
This External Module adds the ability to use the @COLUMNS action tag. This tag will take a checkbox or radio button field and split the choices into the specified number of columns.
[ReadMe.pdf](https://github.com/jhu-redcap/Multi-Column-Checkboxes/files/8611274/ReadMe.pdf)


@COLUMNS Action Tag
Overview:
The @COLUMNS action tag takes a single integer parameter and splits a set of options (checkboxes and/or radio buttons) into the specified number of columns.
Usage:
Action Tag Section: @COLUMNS=5
Specifications:
•	The sorting is driven by the field alignment:
o	If a Vertical field alignment is chosen, the options will be sorted vertically (first column will contain options 1, 2, 3…).
o	If a Horizontal field alignment is chosen, the options will be sorted horizontally (first row will contain options 1, 2, 3…).
•	The width of the columns is driven by the length of the labels.
•	It is recommended that careful testing is done to ensure the menu labels and number of columns work well together and present the desired layout.
•	Using a large number of columns may have unintended consequences in regards to the page width / layout. However, a field with very short labels may be able to accommodate many columns.
•	Works with field embedding. The field using @COLUMNS can be embedded. Also, the menu being split can include embedded fields (e.g. including a text field after an “Other” option).
•	When downloading as a PDF, you MUST use the “(via browser’s Save as PDF)” option.
 
•	If an invalid parameter value is provided (0, -1, a…) a default of 1-column will be used.



See next page for examples
 
Example:
 	
Resulting Output (checkboxes): Note how the options are organized vertically based on the field alignment.
 
Resulting Output (radio buttons): Note how the options are organized horizontally based on the field alignment.
 
